<?php
function withAuth()
{
    global $pdo;

    $encoded_token = null;
    if (isset($_SERVER['HTTP_X_AUTHORIZATION'])) {
        $encoded_token = $_SERVER['HTTP_X_AUTHORIZATION'];
    }

    if ($encoded_token == null) {
        $res = new RESPONSE();
        $res->setStatus(401);
        $res->setMessage("You have not provided any token.");
        $res->send();
    }

    $token = base64_decode($encoded_token, true);
    if (base64_encode($token) != $encoded_token) {
        $res = new RESPONSE();
        $res->setStatus(400);
        $res->setMessage("Invalid token format.");
        $res->send();
    }

    $data = explode("-", $token);
    $stmt = $pdo->prepare("SELECT password FROM utenti WHERE email = :email");
    $stmt->execute(array(":email" => $data[0]));

    if ($stmt->rowCount() != 1) {
        $res = new RESPONSE();
        $res->setStatus(400);
        $res->setMessage("No user has registered with that email.");
        $res->send();
    }

    $password = $stmt->fetchColumn(0);

    $expected = md5($data[0] . $password);
    if ($expected != $data[1]) {
        $res = new RESPONSE();
        $res->setStatus(401);
        $res->setMessage("You have not been authenticated.");
        $res->send();
    }

    return $data;
}
