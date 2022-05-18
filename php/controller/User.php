<?php
    include_once "../../includes/dbConn.php";
    include_once "../middleware/withauth.php";
    include_once '../class/DB.class.php';
    include_once '../class/PCTO.class.php';
    include_once './/class/RESPONSE.class.php';
    include_once '../includes/dbConn.php';
    
    Class User{
        public function getUsers(){
            global $pdo, $class;

            if(!$class->isAdmin()){
                $res = new RESPONSE();
                $res->setStatus(401);
                $res->setMessage("Operation not permitted.");
                $res->setError(1);
                $res->send();
            }

            $stmt = $class->select("*", "utenti");
    
            if ($stmt->rowCount() < 1) {
                $res = new RESPONSE();
                $res->setStatus(400);
                $res->setMessage("No users found.");
                $res->setError(1);
                $res->send();
            }
    
            $result = array();
            
            while($row = $stmt->fetch())
            {
                $user = array(
                    'id' => $row['idUtente'],
                    'nome' => $row['nome'],
                    'cognome' => $row['cognome'],
                    'email' => $row['email'],
                    'img' => $row['imgProfilo']
                );

                array_push($result, $row);
            }
    
            $response = new RESPONSE();
            $response->setStatus(200);
            $response->setSuccess();
            $response->setData($result);
            $response->send();
        }

        public function getUser(){
            global $pdo, $class;
            $token = withAuth($pdo);
            
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
    
                $stmt = $class->select('*', 'utenti', array('idUtente' => $id));
                $stmt->execute();
    
                if ($stmt->rowCount() != 1) {
                    $res = new RESPONSE();
                    $res->setStatus(400);
                    $res->setMessage("No user found with that id.");
                    $res->setError(1);
                    $res->send();
                }
    
                $result = $stmt->fetch();
    
                $user = array(
                    'id' => $result['idUtente'],
                    'nome' => $result['nome'],
                    'cognome' => $result['cognome'],
                    'email' => $result['email'],
                    'img' => $result['imgProfilo']
                );
    
                
                $response = new RESPONSE();
                $response->setStatus(200);
                $response->setSuccess();
                $response->setData($user);
                $response->send();
            }
    
            $user = $class->getUserInfo($token[0]);
            $response = new RESPONSE();
            $response->setStatus(200);
            $response->setSuccess();
            $response->setData($user);
            $response->send();
        }

        public function addUser(){
            global $pdo, $class;

            if(!$class->isAdmin()){
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
            
            if ($class->userAlreadyExists($email)) {
                $res = new RESPONSE();
                $res->setStatus(400);
                $res->setMessage("User already exists.");
                $res->setError(1);
                $res->send();
            }
            
            if (!$class->register($daInserire)) {
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
            $res->setMessage("User registered.");
            $res->setData($user);
            $res->send();
        }

        public function updateUser(){
            global $pdo, $class;

            if(!$class->isAdmin()){
                $res = new RESPONSE();
                $res->setStatus(401);
                $res->setMessage("Operation not permitted.");
                $res->setError(1);
                $res->send();
            }

            $body = json_decode(file_get_contents('php://input'), true);

            if (
                !key_exists("nome", $body) &&
                !key_exists("cognome", $body) &&
                !key_exists("img", $body)
            ) {
                $res = new RESPONSE();
                $res->setStatus(400);
                $res->setMessage("User data are missing.");
                $res->setError(0);
                $res->send();
            }
            
            $daAggiornare = array();

            $idUtente = $_GET["idUtente"];

            if(isset($body['nome'])){
                array_push($daAggiornare, array(
                    "nome" => $body["nome"]
                ));
            }

            if(isset($body['cognome'])){
                array_push($daAggiornare, array(
                    "cognome" => $body["cognome"]
                ));
            }

            if(isset($body['img'])){
                array_push($daAggiornare, array(
                    "img" => $body["img"]
                ));
            }
                        
            if (!$class->userAlreadyExists($email)) {
                $res = new RESPONSE();
                $res->setStatus(400);
                $res->setMessage("User not exists.");
                $res->setError(1);
                $res->send();
            }
            
            $class->updateComplete($daAggiornare, "utenti", array("idUtente" => $idUtente));
            
            $res = new RESPONSE();
            $res->setSuccess();
            $res->setStatus(201);
            $res->setMessage("User updated.");
            $res->send();
        }

        public function deleteUser(){
            global $pdo, $class;
         
            if(!$class->isAdmin()){
                $res = new RESPONSE();
                $res->setStatus(401);
                $res->setMessage("Operation not permitted.");
                $res->setError(1);
                $res->send();
            }

            $idUtente = $_GET["idUtente"];

            if (!$class->userAlreadyExistsById($idUtente)) {
                $res = new RESPONSE();
                $res->setStatus(400);
                $res->setMessage("User not exists.");
                $res->setError(1);
                $res->send();
            }

            $class->delete("utenti", array("idUtente" => $idUtente));

            $res = new RESPONSE();
            $res->setSuccess();
            $res->setStatus(204);
            $res->setMessage("User deleted.");
            $res->send();   
        }
    }
?>
