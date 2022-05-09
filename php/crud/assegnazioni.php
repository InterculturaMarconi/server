<?php
    include '../class/DB.class.php';
    include '../class/PCTO.class.php';
    include '../../includes/dbConn.php';

    $class = new PCTO();
	$class->setPdo($pdo);

    switch($_SERVER['REQUEST_METHOD']){
        case "GET": getAssegnazione(); break;
        case "POST": postAssegnazione(); break;
        case "DELETE": deleteAssegnazione(); break;
        case "PUT": putAssegnazione(); break;
    }

    function getAssegnazione(){
        $idAssegnazione = $_GET['idAssegnazione'];

        $stmt = $class->select('*', 'assegnazioni', array('idAssegnazione' => $idAssegnazione));
		$stmt->execute();
    
		echo $stmt;
    }

    function postAssegnazione(){
        $ksRuolo = $_POST['ksRuolo'];
        $ksUtente = $_POST['ksUtente'];

        $daInserire = array('ksRuolo' => $ksRuolo);

		$stmt = $class->insert($daInserire, 'assegnazioni');
		$stmt->execute();

		echo $stmt;
    }

    function deleteAssegnazione(){
        $idAssegnazione = $_DELETE['idAssegnazione'];

        $stmt = $class->delete("assegnazioni", array("idAssegnazione" => $idAssegnazione));
		$stmt->execute();

        echo $stmt;
    }

    function putAssegnazione(){
        $idAssegnazione = $_PUT["idAssegnazione"];
        $ksRuolo = $_PUT['ksRuolo'];
        $ksUtente = $_PUT['ksUtente'];

        $modifiche = array('ksRuolo' => $ksRuolo, 'ksUtente' => ksUtente);

		$stmt = $class->updateComplete($modifiche, 'assegnazioni', array('idAssegnazione' => $idAssegnazione));
		$stmt->execute();

		echo $stmt;
    }

?>