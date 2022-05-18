<?php

include_once 'class/DB.class.php';
include_once 'class/PCTO.class.php';
include_once 'class/USER.class.php';
include_once 'class/RESPONSE.class.php';
include_once '../includes/dbConn.php';
include_once 'middleware/withmethod.php';

withMethod("POST");

$body = json_decode(file_get_contents('php://input'), true);

$class = new PCTO();
$user = new USER();
$class->setPdo($pdo);
$user->setPdo($pdo);

if(!key_exists("email", $body) || !key_exists("password", $body)) {
	$res = new RESPONSE();
	$res->setStatus(400);
	$res->setMessage("Email or password are missing.");
	$res->setError(0);
	$res->send();
}

$email = $body['email'];
$password = $body['password'];

if (!$class->login($email, $password)) {
	$res = new RESPONSE();
	$res->setStatus(401);
	$res->setMessage("Invalid credentials.");
	$res->setError(2);
	$res->send();
}

$token = base64_encode($email."-".md5($email.md5($password)));
$userdata = $user->getUserInfo($email, $password);

$res = new RESPONSE();
$res->setSuccess();
$res->setStatus(200);
$res->setMessage("User logged in.");
$res->setData(array("token" => $token, "user" => $userdata));
$res->send();

?>
