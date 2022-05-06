<?php

	include 'class/DB.class.php';
	include 'class/PCTO.class.php';
	include '../includes/dbConn.php';

	$email = $_POST['email'];
	$psw = $_POST['psw'];

	$class = new PCTO();
	$class->setPdo($pdo);

	$json = "";

	if($class->login($email,$psw))
	{
		$response = array( 
		    "response" => 202,
		    "session" => md5($email.".".$psw),
		    "emailUser" => $email
		); 
		
		$json = json_encode($response);

	}else
	{
		$response = array( 
		    "response" => 401
		); 
		
		$json = json_encode($response);
	}

	echo $json;




?>