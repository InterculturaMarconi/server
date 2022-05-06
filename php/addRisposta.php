<?php
	
	include '../class/DB.class.php';
	include '../class/PCTO.class.php';
	include '../../includes/dbConn.php';

	$testoRisposta = $_POST['risposta'];
	$idUtente = $_POST['idUtente'];
	$idDomanda = $_POST['idDomanda'];

	$class = new PCTO();
	$class->setPdo($pdo);

	$daInserire = array('testoRisposta' => $testoRisposta, 'ksUtente' => $idUtente, 'ksDomanda' => $idDomanda);

	$stmt = $class->insert($daInserire,'risposte');
	$stmt->execute();

	if($stmt->rowCount() > 0)
	{
		$response = array(
			"response" => 202;
		);

		$json = json_encode($response);
	}else
	{
		$response = array(
			"response" => 400;
		);

		$json = json_encode($response);
	}

	echo $json;
?>