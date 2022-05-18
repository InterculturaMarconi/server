<?php
    Class UserController {
        public function getUsers(){
            global $pcto, $userRepo;

            if(!$pcto->isAdmin()){
                $res = new RESPONSE();
                $res->setStatus(401);
                $res->setMessage("Operation not permitted.");
                $res->setError(1);
                $res->send();
            }

            $users = $userRepo->getAll();
    
            if (count($users) == 0) {
                $res = new RESPONSE();
                $res->setStatus(400);
                $res->setMessage("No users found.");
                $res->setError(1);
                $res->send();
            }
    
            $response = new RESPONSE();
            $response->setStatus(200);
            $response->setSuccess();
            $response->setData($users);
            $response->send();
        }

        public function getUser(){
            global $pcto, $userRepo;
            $token = withAuth();
            
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
    
                $user = $userRepo->get($id);
    
                if ($user == NULL) {
                    $res = new RESPONSE();
                    $res->setStatus(400);
                    $res->setMessage("No user found with that id.");
                    $res->setError(1);
                    $res->send();
                }    
                
                $response = new RESPONSE();
                $response->setStatus(200);
                $response->setSuccess();
                $response->setData($user);
                $response->send();
            }
    
            $user = $userRepo->getByEmail($token[0]);

            $response = new RESPONSE();
            $response->setStatus(200);
            $response->setSuccess();
            $response->setData($user);
            $response->send();
        }

        public function addUser(){
            global $pcto, $userRepo;

            if(!$pcto->isAdmin()){
                $res = new RESPONSE();
                $res->setStatus(401);
                $res->setMessage("Operation not permitted.");
                $res->setError(1);
                $res->send();
            }

            $body = json_decode(file_get_contents('php://input'), true);

            if (
                !key_exists("nome", $body) ||
                !key_exists("cognome", $body) ||
                !key_exists("email", $body) ||
                !key_exists("password", $body)
            ) {
                $res = new RESPONSE();
                $res->setStatus(400);
                $res->setMessage("User data are missing.");
                $res->setError(0);
                $res->send();
            }
            
            $nome = $body['nome'];
            $cognome = $body['cognome'];
            $email = $body['email'];
            $psw = $body['password'];
            $imgProfilo = $body['img'] ?? "";
            
            $daInserire = array('nome' => $nome, 'cognome' => $cognome, 'email' => $email, 'password' => md5($psw), 'imgProfilo' => $imgProfilo);
            
            if ($userRepo->existsByEmail($email)) {
                $res = new RESPONSE();
                $res->setStatus(400);
                $res->setMessage("User already exists.");
                $res->setError(1);
                $res->send();
            }
            
            if (!$userRepo->create($daInserire)) {
                $res = new RESPONSE();
                $res->setStatus(500);
                $res->setMessage("Error while registering.");
                $res->send();
            }
            
            $user = array(
                "nome" => $nome,
                "cognome" => $cognome,
                "email" => $email,
                "img" => $imgProfilo
            );
            
            $res = new RESPONSE();
            $res->setSuccess();
            $res->setStatus(201);
            $res->setMessage("User added.");
            $res->setData($user);
            $res->send();
        }

        // public function updateUser(){
        //     global $pdo, $pcto;

        //     if(!$pcto->isAdmin()){
        //         $res = new RESPONSE();
        //         $res->setStatus(401);
        //         $res->setMessage("Operation not permitted.");
        //         $res->setError(1);
        //         $res->send();
        //     }

        //     $body = json_decode(file_get_contents('php://input'), true);

        //     if (
        //         !key_exists("nome", $body) &&
        //         !key_exists("cognome", $body) &&
        //         !key_exists("img", $body)
        //     ) {
        //         $res = new RESPONSE();
        //         $res->setStatus(400);
        //         $res->setMessage("User data are missing.");
        //         $res->setError(0);
        //         $res->send();
        //     }
            
        //     $daAggiornare = array();

        //     $idUtente = $_GET["id"];

        //     if(isset($body['nome'])){
        //         array_push($daAggiornare, array(
        //             "nome" => $body["nome"]
        //         ));
        //     }

        //     if(isset($body['cognome'])){
        //         array_push($daAggiornare, array(
        //             "cognome" => $body["cognome"]
        //         ));
        //     }

        //     if(isset($body['img'])){
        //         array_push($daAggiornare, array(
        //             "img" => $body["img"]
        //         ));
        //     }
                        
        //     if (!$pcto->userAlreadyExists($email)) {
        //         $res = new RESPONSE();
        //         $res->setStatus(400);
        //         $res->setMessage("User not exists.");
        //         $res->setError(1);
        //         $res->send();
        //     }
            
        //     $pcto->updateComplete($daAggiornare, "utenti", array("idUtente" => $idUtente));
            
        //     $res = new RESPONSE();
        //     $res->setSuccess();
        //     $res->setStatus(201);
        //     $res->setMessage("User updated.");
        //     $res->send();
        // }

        public function deleteUser(){
            global $pcto, $userRepo;
         
            if(!$pcto->isAdmin()){
                $res = new RESPONSE();
                $res->setStatus(401);
                $res->setMessage("Operation not permitted.");
                $res->setError(1);
                $res->send();
            }

            $idUtente = $_GET["id"];

            if (!$userRepo->exists($idUtente)) {
                $res = new RESPONSE();
                $res->setStatus(400);
                $res->setMessage("User does not exist.");
                $res->setError(1);
                $res->send();
            }

            if (!$userRepo->delete($idUtente)) {
                $res = new RESPONSE();
                $res->setStatus(500);
                $res->setMessage("Error while deleting.");
                $res->send();
            }

            $res = new RESPONSE();
            $res->setSuccess();
            $res->setStatus(204);
            $res->setMessage("User deleted.");
            $res->send();   
        }
    }
?>
