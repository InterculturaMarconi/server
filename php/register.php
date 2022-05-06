<?php

	include 'class/DB.class.php';
	include 'class/PCTO.class.php';
	include '../includes/dbConn.php';

	$nome = $_POST['nome'];
	$cognome = $_POST['cognome'];
	$email = $_POST['email'];
	$psw = $_POST['psw'];
	$imgProfilo = $_POST['img'];

	$class = new PCTO();
	$class->setPdo($pdo);
	
	$json = "";

	$daInserire = array('nome' => $nome, 'cognome' => $cognome, 'email' => $email, 'password' => md5($psw), 'imgProfilo' => $imgProfilo);

	if(!$class->userAlreadyExists($email))
	{
		if($class->register($daInserire))
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
	}else
	{
		$response = array( 
		    "response" => 400
		); 
		
		$json = json_encode($response);
	}
	

	echo $json;




?>