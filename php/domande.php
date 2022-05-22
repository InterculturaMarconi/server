<?php
include_once 'PCTO.php';
include_once 'RESPONSE.php';

include_once 'repository/Form.php';
include_once 'repository/Question.php';

include_once 'controller/Question.php';

include_once '../includes/dbConn.php';
include_once 'middleware/withauth.php';

include_once 'cors.php';


$pcto = new PCTO($pdo);

$formRepo = new Form($pdo);
$questionRepo = new Question($pdo);

$questionController = new QuestionController();

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
    global $questionController;

    if (isset($_GET["action"])) {
        switch ($_GET["action"]) {
            case "all":
                $questionController->getAll();
                break;
            default:(new RESPONSE())
                    ->setStatus(400)
                    ->setMessage("Invalid action")
                    ->send();
                break;
        }
    }

    $questionController->getQuestion();
}

function POST()
{
    global $questionController;  
    $questionController->addQuestion(); 
}

function PUT()
{
    global $questionController;
    $questionController->updateQuestion();
}

function DELETE()
{
    global $questionController;
    $questionController->deleteQuestion();
}
