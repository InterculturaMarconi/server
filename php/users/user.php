<?php

	include '../class/DB.class.php';
	include '../class/PCTO.class.php';
	include '../class/USER.class.php';
	include '../../includes/dbConn.php';

	$email = $_POST['email'];
	$sessionUtente = $_POST['sessUser'];

	$class = new PCTO();
	$class->setPdo($pdo);
	$user = new USER();
	$user->setPdo($pdo);

	$conditions = array('email' => $email);
	$password = $user->getItem('password', $conditions);

	$json = "";

	$daControllare = md5($email.".".$password);

	if($daControllare == $sessionUtente)
	{
		$json = json_encode($user->getuserInfo($email));
	}
	else
	{
		$response = array( 
		    "response" => 403
		); 
		
		$json = json_encode($response);
	}

	echo $json;
?>