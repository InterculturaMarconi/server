<?php
    include '../class/DB.class.php';
    include '../class/PCTO.class.php';
    include '../../includes/dbConn.php';

    $class = new PCTO();
	$class->setPdo($pdo);

    switch($_SERVER['REQUEST_METHOD']){
        case "GET": getRuolo(); break;
        case "POST": postRuolo(); break;
        case "DELETE": deleteRuolo(); break;
        case "PUT": putRuolo(); break;
    }

    function getRuolo(){
        $idRuolo = $_GET['idRuolo'];

        $stmt = $class->select('*', 'ruoli', array('idRuolo' => $idRuolo));
		$stmt->execute();
    
		echo $stmt;
    }

    function postRuolo(){
        $denominazione = $_POST['denominazione'];

        $daInserire = array('denominazione' => $denominazione);

		$stmt = $class->insert($daInserire, 'ruoli');
		$stmt->execute();

		echo $stmt;
    }

    function deleteRuolo(){
        $idRuolo = $_DELETE['idRuolo'];

        $stmt = $class->delete("ruoli", array("idRuolo" => $idRuolo));
		$stmt->execute();

        echo $stmt;
    }

    function putRuolo(){
        $idRuolo = $_PUT["idRuolo"];
        $denominazione = $_PUT['denominazione'];

        $modifiche = array('denominazione' => $denominazione);

		$stmt = $class->updateComplete($modifiche, 'ruoli', array('idRuolo' => $idRuolo));
		$stmt->execute();

		echo $stmt;
    }

?>