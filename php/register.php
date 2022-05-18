<?php

include_once 'RESPONSE.php';
include_once 'repository/User.php';
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
	$res->setError(0);
	$res->send();
}

$nome = $body['nome'];
$cognome = $body['cognome'];
$email = $body['email'];
$psw = $body['password'];
$imgProfilo = $body['img'] ?? "";

$userRepo = new User($pdo);

$daInserire = array('nome' => $nome, 'cognome' => $cognome, 'email' => $email, 'password' => md5($psw), 'imgProfilo' => $imgProfilo);

if ($userRepo->existsByEmail($email)) {
	$res = new RESPONSE();
	$res->setStatus(400);
	$res->setMessage("User already exists.");
	$res->setError(1);
	$res->send();
}

if (!$userRepo->create($daInserire)) {
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

setcookie("auth-token", $token, time() + (86400 * 30), "/", "pctomarconi.altervista.org", true, true);

$res = new RESPONSE();
$res->setSuccess();
$res->setStatus(201);
$res->setMessage("User registered.");
$res->setData(array("token" => $token, "user" => $user));
$res->send();

?>
