<?php
include_once 'PCTO.php';
include_once 'RESPONSE.php';

include_once 'controller/User.php';
include_once 'controller/Role.php';

include_once 'repository/User.php';
include_once 'repository/Role.php';

include_once '../includes/dbConn.php';
include_once 'middleware/withauth.php';

include_once 'cors.php';

$pcto = new PCTO($pdo);
$userRepo = new User($pdo);
$roleRepo = new Role($pdo);

$userController = new UserController();
$roleController = new RoleController();

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":GET();
        break;
    case "POST":POST();
        break;
    case "PUT":PUT();
        break;
    case "DELETE":DELETE();
        break;
}

function GET()
{
    global $userController, $roleController;

    if (isset($_GET["action"])) {
        switch ($_GET["action"]) {
            case "roles":
                $roleController->getRoles();
                break;

            case "all":
                $userController->getUsers();
                break;

            default:(new RESPONSE())
                    ->setStatus(400)
                    ->setMessage("Invalid action")
                    ->send();
        }
    }

    $userController->getUser();
}

function POST()
{
    global $userController, $roleController;

    if (isset($_GET["action"])) {
        switch ($_GET["action"]) {
            case "roles":
                $roleController->addRole();
                break;

            default:(new RESPONSE())
                    ->setStatus(400)
                    ->setMessage("Invalid action")
                    ->send();
        }
    }

    $userController->addUser();
}
function PUT()
{
    global $userController, $roleController;

    if (isset($_GET["action"])) {
        switch ($_GET["action"]) {
            default:(new RESPONSE())
                    ->setStatus(400)
                    ->setMessage("Invalid action")
                    ->send();
        }
    }

    $userController->updateUser();
}
function DELETE()
{
    global $userController, $roleController;

    if (isset($_GET["action"])) {
        switch ($_GET["action"]) {
            case "roles":
                $roleController->deleteRole();
                break;

            default:(new RESPONSE())
                    ->setStatus(400)
                    ->setMessage("Invalid action")
                    ->send();
        }
    }

    $userController->deleteUser();
}
