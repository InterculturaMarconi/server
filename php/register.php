<?php

include_once 'class/DB.class.php';
include_once 'class/PCTO.class.php';
include_once 'class/RESPONSE.class.php';
include_once '../includes/dbConn.php';

$body = json_decode(file_get_contents('php://input'), true);

if (
	!key_exists("nome", $body) ||
	!key_exists("cognome", $body) ||
	!key_exists("email", $body) ||
	!key_exists("password", $body)
) {
	$res = new RESPONSE();
	$res->setStatus(400);
	$res->setMessage("User data are missing.");
	$res->send();
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
	$res = new RESPONSE();
	$res->setStatus(400);
	$res->setMessage("User already exists.");
	$res->send();
}

if (!$class->register($daInserire)) {
	$res = new RESPONSE();
	$res->setStatus(500);
	$res->setMessage("Error while registering.");
	$res->send();
}

$token = base64_encode($email."-".md5($email.md5($psw)));
$user = array(
	"nome" => $nome,
	"cognome" => $cognome,
	"email" => $email,
	"img" => $imgProfilo
);

$res = new RESPONSE();
$res->setSuccess();
$res->setStatus(201);
$res->setMessage("User registered.");
$res->setData(array("token" => $token, "user" => $user));
$res->send();

?>
