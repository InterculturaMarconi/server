<?php
class AnswerContorller
{
    public function getAll()
    {
        global $pcto, $answerRepo, $formRepo;

        $formId = $_GET["form"];
        $form = $formRepo->get($formId);

        if ($form == null) {
            (new RESPONSE())
                ->setStatus(400)
                ->setMessage("Form not found")
                ->send();
        }

        if (!$pcto->isObjectiveAdmin($form["id_obiettivo"])) {
            (new RESPONSE())
                ->setStatus(403)
                ->setMessage("You are not allowed to see this form")
                ->send();
        }

        $answers = $answerRepo->getByForm($formId);

        if (count($answers) == 0) {
            (new RESPONSE())
                ->setStatus(400)
                ->setMessage("No answers found for that form")
                ->send();
        }

        (new RESPONSE())
            ->setStatus(200)
            ->setData($answers)
            ->send();
    }

    public function getAllByQuestion()
    {
        global $pcto, $answerRepo, $questionRepo, $formRepo;

        $questionId = $_GET["question"];
        $question = $questionRepo->get($questionId);

        if ($question == null) {
            (new RESPONSE())
                ->setStatus(400)
                ->setMessage("Question not found")
                ->send();
        }

        $form = $formRepo->get($question["id_form"]);
        if (!$pcto->isObjectiveAdmin($form["id_obiettivo"])) {
            (new RESPONSE())
                ->setStatus(403)
                ->setMessage("You are not allowed to see this form")
                ->send();
        }

        $answers = $answerRepo->getByQuestion($questionId);

        if (count($answers) == 0) {
            (new RESPONSE())
                ->setStatus(400)
                ->setMessage("No answers found for that question")
                ->send();
        }

        (new RESPONSE())
            ->setStatus(200)
            ->setData($answers)
            ->send();
    }

    public function getAllByUser()
    {
        global $pcto, $answerRepo, $userRepo, $formRepo;

        $userId = $_GET["user"];
        $user = $userRepo->get($userId);

        if ($user == null) {
            (new RESPONSE())
                ->setStatus(400)
                ->setMessage("User not found")
                ->send();
        }

        $formId = $_GET["form"];
        $form = $formRepo->get($formId);

        if ($form == null) {
            (new RESPONSE())
                ->setStatus(400)
                ->setMessage("Form not found")
                ->send();
        }

        if (!$pcto->isObjectiveAdmin($form["id_obiettivo"])) {
            (new RESPONSE())
                ->setStatus(403)
                ->setMessage("You are not allowed to see this form")
                ->send();
        }

        $answers = $answerRepo->getByUser($userId, $formId);

        if (count($answers) == 0) {
            (new RESPONSE())
                ->setStatus(400)
                ->setMessage("No answers found for that user")
                ->send();
        }
    }

    public function getAnswer()
    {
        global $pcto, $answerRepo, $questionId, $formRepo;

        $answerId = $_GET["id"];
        $answer = $answerRepo->get($answerId);

        if ($answer == null) {
            (new RESPONSE())
                ->setStatus(400)
                ->setMessage("Answer not found")
                ->send();
        }

        $question = $questionRepo->get($answer["id_domanda"]);
        $form = $formRepo->get($question["id_form"]);

        if (!$pcto->isObjectiveAdmin($form["id_obiettivo"])) {
            (new RESPONSE())
                ->setStatus(403)
                ->setMessage("You are not allowed to see this form")
                ->send();
        }

        (new RESPONSE())
            ->setStatus(200)
            ->setData($answer)
            ->send();
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
            (new RESPONSE())
                ->setStatus(400)
                ->setMessage("Form not found")
                ->send();
        }
        
        $question = $questionRepo->get($questionId);
        if ($question == null) {
            (new RESPONSE())
                ->setStatus(400)
                ->setMessage("Question not found")
                ->send();
        }

        if (!$pcto->hasObjectivePermission($form["id_obiettivo"])) {
            (new RESPONSE())
                ->setStatus(403)
                ->setMessage("You are not allowed to see this form")
                ->send();
        }

        $email = withAuth()[0];
        $userId = $userRepo->getByEmail($email)["id"];

        $answer = $answerRepo->add($formId, $questionId, $testo, $userId);
    }
}
