<?php

include 'class/DB.class.php';
include 'class/PCTO.class.php';
include '../includes/dbConn.php';

include 'middleware/postonly.php';

$body = json_decode(file_get_contents('php://input'), true);

if (
	!key_exists("nome", $body) ||
	!key_exists("cognome", $body) ||
	!key_exists("email", $body) ||
	!key_exists("password", $body)
) {
	echo json_encode(array("error" => "Body fileds are missing."));
	http_response_code(400);
	exit();
}

$nome = $body['nome'];
$cognome = $body['cognome'];
$email = $body['email'];
$psw = $body['password'];
$imgProfilo = $body['img'] ?? "";

$class = new PCTO();
$class->setPdo($pdo);

$daInserire = array('nome' => $nome, 'cognome' => $cognome, 'email' => $email, 'password' => md5($psw), 'imgProfilo' => $imgProfilo);

if ($class->userAlreadyExists($email)) {
	echo json_encode(array("error" => "User already exists."));
	http_response_code(400);
	exit();
}

if (!$class->register($daInserire)) {
	echo json_encode(array("error" => "Error while registering."));
	http_response_code(500);
	exit();
}

$token = md5($email.".".md5($psw));
$user = array(
	"nome" => $nome,
	"cognome" => $cognome,
	"email" => $email,
	"img" => $imgProfilo
);

echo json_encode(array("token" => $token, "user" => $user));
http_response_code(201);
