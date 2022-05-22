<?php
class QuestionController
{
    public function getAll() 
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

        if (!$pcto->hasObjectivePermission($form["id_obiettivo"])) {
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
            !isset($body["id_form"]) ||
            !isset($body["testo"]) ||
            !isset($body["tipo"])
        ) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("Question data are missing.");
            $res->setError(0);
            $res->send();
        }

        $idForm = $body["id_form"];
        $testo = $body["testo"];
        $tipo = $body["tipo"];

        $form = $formRepo->get($idForm);

        if ($form == NULL) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("No forms found associated with this id.");
            $res->setError(1);
            $res->send();
        }

        if (!$pcto->isObjectiveAdmin($form["id_obiettivo"])) {
            $res = new RESPONSE();
            $res->setStatus(401);
            $res->setMessage("Operation not permitted.");
            $res->setError(1);
            $res->send();
        }

        $questionRepo->add($idForm, $testo, $tipo);

        $res = new RESPONSE();
        $res->setSuccess();
        $res->setStatus(201);
        $res->setMessage("Form created.");
        $res->send();
    }

    // TODO: updateQuestion()
    public function updateQuestion() 
    {
        global $pcto, $questionRepo;
    }

    // TODO: updateQuestion()
    public function deleteQuestion() 
    {
        global $pcto, $questionRepo;
    }
}