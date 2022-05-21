<?php
class QuestionController
{
    public function getQuestions() 
    {
        global $pcto, $formRepo, $questionRepo;

        $formId = $_GET["form"];
        $form = $formRepo->get($formId);

        if ($form == NULL) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("No forms found associated with this id.");
            $res->setError(1);
            $res->send();
        }

        if (!$pcto->isObjectiveAdmin($form["idObiettivo"])) {
            $res = new RESPONSE();
            $res->setStatus(401);
            $res->setMessage("Operation not permitted.");
            $res->setError(1);
            $res->send();
        }

        $data = $questionRepo->getByForm($formId);
        if (count($data) == 0) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("No questions found for that form.");
            $res->setError(1);
            $res->send();
        }

        $response = new RESPONSE();
        $response->setStatus(200);
        $response->setSuccess();
        $response->setData($data);
        $response->send();
    }

    public function getQuestion() 
    {
        global $pcto, $formRepo, $questionRepo;

        $id = $_GET["id"];
        $domanda = $questionRepo->get($id);

        if ($domanda == NULL) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("No questions found associated with this id.");
            $res->setError(1);
            $res->send();
        }

        $formId = $domanda["form_id"];
        $form = $formRepo->get($formId);

        if ($form == NULL) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("No forms found associated with this id.");
            $res->setError(1);
            $res->send();
        }

        if (!$pcto->isObjectiveAdmin($form["objective_id"])) {
            $res = new RESPONSE();
            $res->setStatus(401);
            $res->setMessage("Operation not permitted.");
            $res->setError(1);
            $res->send();
        }

        $data = $questionRepo->getByForm($formId);
        if (count($data) == 0) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("No questions found for that form.");
            $res->setError(1);
            $res->send();
        }

        $response = new RESPONSE();
        $response->setStatus(200);
        $response->setSuccess();
        $response->setData($data);
        $response->send();
    }

    public function addQuestion() 
    {
        global $pcto, $formRepo, $questionRepo;

        $body = json_decode(file_get_contents('php://input'), true);

        if (
            !isset($body["form_id"]) ||
            !isset($body["text"]) ||
            !isset($body["type"])
        ) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("Question data are missing.");
            $res->setError(0);
            $res->send();
        }

        $idForm = $body["form_id"];
        $testo = $body["text"];
        $tipo = $body["tipo"];

        $form = $formRepo->get($idForm);

        $res = new RESPONSE();
        $res->setSuccess();
        $res->setStatus(201);
        $res->setMessage("Form created.");
        $res->send();
    }

    public function updateQuestion() 
    {
        global $pcto, $questionRepo;
    }

    public function deleteQuestion() 
    {
        global $pcto, $questionRepo;
    }
}