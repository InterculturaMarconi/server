<?php
    function withMethod(string $method) {
        if ($_SERVER['REQUEST_METHOD'] != $method) {
            $res = new RESPONSE();
            $res->setStatus(405);
            $res->setMessage("Method not allowed.");
            $res->send();
        }
    }
?>