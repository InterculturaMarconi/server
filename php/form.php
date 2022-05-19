<?php
    include_once 'PCTO.php';
    include_once 'RESPONSE.php';

    include_once 'controller/Form.php'
    include_once 'repository/Form.php'
    
    include_once '../includes/dbConn.php';
    include_once 'middleware/withauth.php';

    $pcto = new PCTO($pdo);
    $formRepo = new Form($pdo);

    $formController = new FormController();

    switch($_SERVER["REQUEST_METHOD"]) {
        case "GET": GET(); break;
        case "POST": POST(); break;
        case "PUT": PUT(); break;
        case "DELETE": DELETE(); break;
    }

    function GET() {
        global $formController;

        if(isset($_GET["action"])) {
            switch ($_GET["action"]) {
                case "all":
                    if(isset($_GET["id"])) {
                        $formController->getFormsByObiettivo($_GET["id"]);
                    } 
                        
                    $formController->getForms();
                    break;

                default: (new RESPONSE())
                    ->setStatus(400)
                    ->setMessage("Invalid action")
                    ->send();
            }
        }

        $formController->getUser();
    }

    function POST() {
        global $formController;

        if(isset($_GET["action"])) {
            switch ($_GET["action"]) {
                case "roles":
                    $roleController->addRole();
                    break;

                default: (new RESPONSE())
                    ->setStatus(400)
                    ->setMessage("Invalid action")
                    ->send();
            }
        }

        $userController->addUser();
    }

    function PUT() {
        global $userController, $roleController;

        if(isset($_GET["action"])) {
            switch ($_GET["action"]) {
                default: (new RESPONSE())
                    ->setStatus(400)
                    ->setMessage("Invalid action")
                    ->send();
            }
        }

        $userController->updateUser();
    }

    function DELETE() {
        global $userController, $roleController;

        if(isset($_GET["action"])) {
            switch ($_GET["action"]) {
                case "roles":
                    $roleController->deleteRole();
                    break;

                default: (new RESPONSE())
                    ->setStatus(400)
                    ->setMessage("Invalid action")
                    ->send();
            }
        }

        $userController->deleteUser();
    }
?>