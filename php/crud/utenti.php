<?php
    include '../class/DB.class.php';
    include '../class/PCTO.class.php';
    include '../../includes/dbConn.php';

    $class = new PCTO();
	$class->setPdo($pdo);

    switch($_SERVER['REQUEST_METHOD']){
        case "GET": getUtente(); break;
        case "POST": postUtente(); break;
        case "DELETE": deleteUtente(); break;
        case "PUT": putUtente(); break;
    }

    function getUtente(){
        $idUtente = $_GET['idUtente'];

        $stmt = $class->select('*', 'utenti', array('idUtente' => $idUtente));
		$stmt->execute();
    
		echo $stmt;
    }

    function postRuolo(){
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $imgProfilo = $_POST['imgProfilo'];

        $daInserire = array('nome' => $nome, 'cognome' => $cognome, 'email' => $email, 'password' => $password, 'imgProfilo' => $imgProfilo);

		$stmt = $class->insert($daInserire, 'utenti');
		$stmt->execute();

		echo $stmt;
    }

    function deleteUtente(){
        $idUtente = $_DELETE['idUtente'];

        $stmt = $class->delete("utenti", array("idUtente" => $idUtente));
		$stmt->execute();

        echo $stmt;
    }

    function putUtente(){
        $modifiche = array();

        $idUtente = $_POST['idUtente'];

        if(isset($_POST['nome'])){
            array_push($modifiche, 'nome' => $_POST['nome']);
        }

        if(isset($_POST['cognome'])){
            array_push($modifiche, 'cognome' => $_POST['cognome']);
        }

        if(isset($_POST['email'])){
            array_push($modifiche, 'email' => $_POST['email']);
        }

        if(isset($_POST['password'])){
            array_push($modifiche, 'password' => $_POST['password']);
        }

        if(isset($_POST['imgProfilo'])){
            array_push($modifiche, 'imgProfilo' => $_POST['imgProfilo']);
        }

		$stmt = $class->updateComplete($modifiche, 'utenti', array('idUtente' => $idUtente));
		$stmt->execute();

		echo $stmt;
    }

?>