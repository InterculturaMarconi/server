<?php
class AnswerController
{
    public function getAll()
    {
        global $pcto, $answerRepo, $formRepo;

        $formId = $_GET["form"];
        $form = $formRepo->get($formId);

        if ($form == null) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("Form not found");
            $res->send();
        }

        if (!$pcto->isObjectiveAdmin($form["id_obiettivo"])) {
            $res = new RESPONSE();
            $res->setStatus(403);
            $res->setMessage("You are not allowed to see this form");
            $res->send();
        }

        $answers = $answerRepo->getByForm($formId);

        if (count($answers) == 0) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("No answers found for that form");
            $res->send();
        }

        $res = new RESPONSE();
        $res->setStatus(200);
        $res->setData($answers);
        $res->send();
    }

    public function getAllByQuestion()
    {
        global $pcto, $answerRepo, $questionRepo, $formRepo;

        $questionId = $_GET["question"];
        $question = $questionRepo->get($questionId);

        if ($question == null) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("Question not found");
            $res->send();
        }

        $form = $formRepo->get($question["id_form"]);
        if (!$pcto->isObjectiveAdmin($form["id_obiettivo"])) {
            $res = new RESPONSE();
            $res->setStatus(403);
            $res->setMessage("You are not allowed to see this form");
            $res->send();
        }

        $answers = $answerRepo->getByQuestion($questionId);

        if (count($answers) == 0) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("No answers found for that question");
            $res->send();
        }

        $res = new RESPONSE();
        $res->setStatus(200);
        $res->setData($answers);
        $res->send();
    }

    public function getAllByUser()
    {
        global $pcto, $answerRepo, $userRepo, $formRepo;

        $userId = $_GET["user"];
        $user = $userRepo->get($userId);

        if ($user == null) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("User not found");
            $res->send();
        }

        $formId = $_GET["form"];
        $form = $formRepo->get($formId);

        if ($form == null) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("Form not found");
            $res->send();
        }

        if (!$pcto->isObjectiveAdmin($form["id_obiettivo"])) {
            $res = new RESPONSE();
            $res->setStatus(403);
            $res->setMessage("You are not allowed to see this form");
            $res->send();
        }

        $answers = $answerRepo->getByUser($userId, $formId);

        if (count($answers) == 0) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("No answers found for that user");
            $res->send();
        }

        $res = new RESPONSE();
        $res->setStatus(200);
        $res->setData($answers);
        $res->send();
    }

    public function getAnswer()
    {
        global $pcto, $answerRepo, $questionId, $formRepo;

        $answerId = $_GET["id"];
        $answer = $answerRepo->get($answerId);

        if ($answer == null) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("Question not found");
            $res->send();
        }

        $question = $questionRepo->get($answer["id_domanda"]);
        $form = $formRepo->get($question["id_form"]);

        if (!$pcto->isObjectiveAdmin($form["id_obiettivo"])) {
            $res = new RESPONSE();
            $res->setStatus(403);
            $res->setMessage("You are not allowed to see this form");
            $res->send();
        }

        $res = new RESPONSE();
        $res->setStatus(200);
        $res->setData($answer);
        $res->send();
    }

    public function addAnswer()
    {
        global $pcto, $answerRepo, $questionRepo, $formRepo, $userRepo;

        $body = json_decode(file_get_contents('php://input'), true);

        if (
            !isset($body["id_form"]) ||
            !isset($body["testo"]) ||
            !isset($body["id_domanda"])
        ) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("Answer data are missing.");
            $res->setError(0);
            $res->send();
        }

        $formId = $body["id_form"];
        $questionId = $body["id_domanda"];
        $testo = $body["testo"];

        $form = $formRepo->get($formId);
        if ($form == null) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("Form not found");
            $res->send();
        }

        $question = $questionRepo->get($questionId);
        if ($question == null) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("Question not found");
            $res->send();
        }

        if (!$pcto->hasObjectivePermission($form["id_obiettivo"])) {
            $res = new RESPONSE();
            $res->setStatus(403);
            $res->setMessage("You are not allowed to see this form");
            $res->send();
        }

        $email = withAuth()[0];
        $userId = $userRepo->getByEmail($email)["id"];

        $answer = $answerRepo->add($userId, $questionId, $testo);
    }
}
