<?php

/**
 * 
 */
class PCTO extends DB
{
	public function userAlreadyExistsById($id){
		$stmt = $this->select(array('idUtente'),'utenti',array('idUtente' => $id));
		$stmt->execute();

		return $stmt->rowCount() > 0;	
	}

	public function userAlreadyExists($email)
	{
		$stmt = $this->select(array('email'),'utenti',array('email' => $email));
		$stmt->execute();

		return $stmt->rowCount() > 0;
	}

	public function login($email, $psw)
	{
		$stmt = $this->select('*','utenti',array('email' => $email, 'password' => md5($psw)),array('and'));
		$stmt->execute();

		return $stmt->rowCount() > 0;
	}

	public function register($datiUtente)
	{
		$stmt = $this->insert($datiUtente, 'utenti');
		$stmt->execute();

		return $stmt->rowCount() > 0;
	}

	
	public function isAdmin(){
		$token = withAuth($pdo);
		$email = $token[0];

		$sql = "SELECT * FROM ";
		$sql .= "utenti INNER JOIN assegnazione ON utenti.idUtente = assegnazione.ksUtente ";
		$sql .= "INNER JOIN ruoli ON ruoli.idRuolo = assegnazione.ksRuolo"
		$sql .= "WHERE utenti.email = :email AND ruoli.denominazione = 'admin';"

		$stmt = $pdo->prepare($sql);

		$stmt->execute(
			array(":email" => $email)
		);

		return $stmt->rowCount() >= 1;
	}
}

?>