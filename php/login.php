<?php

include 'class/DB.class.php';
include 'class/PCTO.class.php';
include 'class/USER.class.php';
include '../includes/dbConn.php';

include 'middleware/postonly.php';

$body = json_decode(file_get_contents('php://input'), true);

$class = new PCTO();
$user = new USER();
$class->setPdo($pdo);
$user->setPdo($pdo);

if(!key_exists("email", $body) || !key_exists("password", $body)) {
	echo json_encode(array("error" => "Email or password are missing."));
	http_response_code(400);
	exit();
}

$email = $body['email'];
$password = $body['password'];

if (!$class->login($email, $password)) {
	echo json_encode(array("error" => "Invalid credentials."));
	http_response_code(401);
	exit();
}

$token = md5($email.".".md5($password));
$userdata = $user->getuserInfo($email, $password);

echo(json_encode(array("token" => $token, "user" => $userdata)));
http_response_code(201);
