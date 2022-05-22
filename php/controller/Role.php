<?php
class RoleController
{
    public function getRoles()
    {
        global $roleRepo;

        $idUtente = $_GET["id"];
        $roles = $roleRepo->getByUser($idUtente);

        if (count($roles) == 0) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("No roles found associated with this id.");
            $res->setError(1);
            $res->send();
        }

        $response = new RESPONSE();
        $response->setStatus(200);
        $response->setSuccess();
        $response->setData($roles);
        $response->send();
    }

    public function addRole()
    {
        global $pcto;

        if (!$pcto->isAdmin()) {
            $res = new RESPONSE();
            $res->setStatus(401);
            $res->setMessage("Operation not permitted.");
            $res->setError(1);
            $res->send();
        }

        $idRuolo = $_GET["role"];
        $idUtente = $_GET["id"];

        if ($pcto->grant($idUtente, $idRuolo) == 0) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("User already has this role.");
            $res->setError(1);
            $res->send();
        }

        $res = new RESPONSE();
        $res->setSuccess();
        $res->setStatus(201);
        $res->setMessage("Role associated.");
        $res->send();
    }

    public function deleteRole()
    {
        global $pcto;

        if (!$pcto->isAdmin()) {
            $res = new RESPONSE();
            $res->setStatus(401);
            $res->setMessage("Operation not permitted.");
            $res->setError(1);
            $res->send();
        }

        $idRuolo = $_GET["role"];
        $idUtente = $_GET["id"];

        if ($pcto->revoke($idUtente, $idRuolo) == 0) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("User doesn't have this role.");
            $res->setError(1);
            $res->send();
        }

        $res = new RESPONSE();
        $res->setSuccess();
        $res->setStatus(204);
        $res->setMessage("Role deleted.");
        $res->setData($role);
        $res->send();
    }
}
