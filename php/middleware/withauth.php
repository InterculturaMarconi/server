<?php
    function withAuth(PDO $db) {
        $headers = apache_request_headers();

        $encoded_token = NULL;
        if(key_exists('authorization', $headers)) {
            $encoded_token = substr($headers['authorization'], 7);
        } elseif (key_exists('token', $_COOKIE)) {
            $encoded_token = $_COOKIE['token'];
        }

        if ($encoded_token == NULL) {
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
        $stmt = $db->prepare("SELECT password FROM utenti WHERE email = :email");
        $stmt->execute(array(":email" => $data[0]));

        if ($stmt->rowCount() != 1) {
            $res = new RESPONSE();
            $res->setStatus(400);
            $res->setMessage("No user has registered with that email.");
            $res->send();
        }

        $password = $stmt->fetchColumn(0);

        $expected = md5($data[0].$password);
        if ($expected != $data[1]) {
            $res = new RESPONSE();
            $res->setStatus(401);
            $res->setMessage("You have not been authenticated.");
            $res->send();
        }

        return $data;
    }
?>
