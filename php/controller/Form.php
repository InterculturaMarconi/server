<?php   
    class FormController {
        public function getForms(){
            global $pcto, $formRepo;
            
            $idObiettivo = $_GET["id"];

            if(!$pcto->isAdmin()){
                $res = new RESPONSE();
                $res->setStatus(401);
                $res->setMessage("Operation not permitted.");
                $res->setError(1);
                $res->send();
            }

            $forms = $formRepo->getAll();
    
            if (count($forms) == 0) {
                $res = new RESPONSE();
                $res->setStatus(400);
                $res->setMessage("No forms found associated with this id.");
                $res->setError(1);
                $res->send();
            }
    
            $response = new RESPONSE();
            $response->setStatus(200);
            $response->setSuccess();
            $response->setData($forms);
            $response->send();
        }

        public function getFormsByObiettivo(){
            global $pcto, $formRepo;
            
            $idObiettivo = $_GET["idObiettivo"];

            if(!$pcto->hasObjectivePermission($idObiettivo)){
                $res = new RESPONSE();
                $res->setStatus(401);
                $res->setMessage("Operation not permitted.");
                $res->setError(1);
                $res->send();
            }

            $forms = $formRepo->getByObiettivo($idObiettivo);
    
            if (count($forms) == 0) {
                $res = new RESPONSE();
                $res->setStatus(400);
                $res->setMessage("No forms found associated with this id.");
                $res->setError(1);
                $res->send();
            }
    
            $response = new RESPONSE();
            $response->setStatus(200);
            $response->setSuccess();
            $response->setData($forms);
            $response->send();
        }

        public function getForm(){
            global $pcto, $formRepo;
            
            $idForm = $_GET["id"];
            $form = $formRepo->get($idForm);
    
            if(!$pcto->hasObjectivePermission($form["idObiettivo"])){
                $res = new RESPONSE();
                $res->setStatus(401);
                $res->setMessage("Operation not permitted.");
                $res->setError(1);
                $res->send();
            }

            if (count($form) == 0) {
                $res = new RESPONSE();
                $res->setStatus(400);
                $res->setMessage("No forms found associated with this id.");
                $res->setError(1);
                $res->send();
            }
    
            $response = new RESPONSE();
            $response->setStatus(200);
            $response->setSuccess();
            $response->setData($form);
            $response->send();
        }
    
        public function addForm(){
            global $pcto, $formRepo;
    
            if(!$pcto->isAdmin()){
                $res = new RESPONSE();
                $res->setStatus(401);
                $res->setMessage("Operation not permitted.");
                $res->setError(1);
                $res->send();
            }
    
            $idObiettivo = $_POST["idObiettivo"];
            $dataVisualizzazione = $_POST["dataVisualizzazione"];

            $formRepo->add($dataVisualizzazione, $idObiettivo);

            $res = new RESPONSE();
            $res->setSuccess();
            $res->setStatus(201);
            $res->setMessage("Form created.");
            $res->send();
        }

        public function updateForm(){
            global $pcto, $formRepo;
    
            if(!$pcto->isAdmin()){
                $res = new RESPONSE();
                $res->setStatus(401);
                $res->setMessage("Operation not permitted.");
                $res->setError(1);
                $res->send();
            }

            $idForm = $_GET["idForm"];
            $currentForm = $formRepo->get($idForm);

            if(isset($_POST["idObiettivo"]){
                $currentForm["idObiettivo"] = $_POST["idObiettivo"];
            }
           
            if(isset($_POST["dataVisualizzazione"]){
                $currentForm["dataVisualizzazione"] = $_POST["dataVisualizzazione"];
            }

            $formRepo->update($currentForm["dataVisualizzazione"], $currentForm["idObiettivo"]);

            $res = new RESPONSE();
            $res->setSuccess();
            $res->setStatus(201);
            $res->setMessage("Form created.");
            $res->send();
        }
    
        public function deleteForm(){
            global $pcto, $formRepo;
    
            if(!$pcto->isAdmin()){
                $res = new RESPONSE();
                $res->setStatus(401);
                $res->setMessage("Operation not permitted.");
                $res->setError(1);
                $res->send();
            }
    
            $idForm = $_GET["idForm"];
    
            $formRepo->delete($idForm);

            $res = new RESPONSE();
            $res->setSuccess();
            $res->setStatus(204);
            $res->setMessage("Form deleted.");
            $res->send();
        }
    }
?>
