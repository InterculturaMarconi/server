<?php

	include 'class/DB.class.php';
	include 'class/PCTO.class.php';
	include 'class/USER.class.php';
	include '../includes/dbConn.php';

	/*$tabella = 'utenti';
	$condizioniJSON = '{"email":"dcalisti03@gmail.com","id":"5"}';
	$booleanJSON = '{"1":"and"}';*/

	$tabella = $_POST['tabella'];
	$condizioniJSON = $_POST['condizioni'];
	$booleanJSON = $_POST['bool'];
	$condizioni = array();
	$boolean = array();

	$class = new PCTO();
	$class->setPdo($pdo);

	foreach(json_decode($condizioniJSON) as $key => $value)
	{
		$condizioni[$key] = $value;
	}

	foreach(json_decode($booleanJSON) as $value)
	{
		array_push($boolean, $value);
	}

	$stmt = $class->select('*',$tabella,$condizioni,$boolean);
	$stmt->execute();

?>