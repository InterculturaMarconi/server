<?php
include_once 'PCTO.php';
include_once 'RESPONSE.php';

include_once 'repository/Form.php';
include_once 'repository/Question.php';
include_once 'repository/Answer.php';

include_once 'controller/Answer.php';

include_once '../includes/dbConn.php';
include_once 'middleware/withauth.php';

$pcto = new PCTO($pdo);

$formRepo = new Form($pdo);
$questionRepo = new Question($pdo);
$answerRepo = new Answer($pdo);

$answerController = new QuestionController();

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
    global $answerController;

    if (isset($_GET["action"])) {
        switch ($_GET["action"]) {
            case "all":
                $answerController->getAll();
                break;
            case "user":
                $answerController->getAllByUser();
                break;
            case "question":
                $answerController->getAllByQuestion();
                break;
            default:(new RESPONSE())
                    ->setStatus(400)
                    ->setMessage("Invalid action")
                    ->send();
                break;
        }
    }

    $answerController->getAnswer();
}

function POST()
{
    global $answerController;
    $answerController->addAnswer();
}

function PUT()
{
    global $answerController;
    // $answerController->updateQuestion();
}

function DELETE()
{
    global $answerController;
    // $answerController->deleteQuestion();
}
