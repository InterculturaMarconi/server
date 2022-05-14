<?php

/**
 * 
 */
class USER extends DB
{
	public function getItem($item, $conditions, $booleans = [])
	{
		$stmt = $this->select(array($item),'utenti', $conditions, $booleans);
		$stmt->execute();

		while($row = $stmt->fetch())
		{
			return $row[$item];
		}
	}

	public function getUserInfo($email)
	{
		$utente = array();
		
		$stmt = $this->select('*', 'utenti', array('email' => $email));
		$stmt->execute();

		while($row = $stmt->fetch())
		{
			$utente['id'] = $row['idUtente'];
			$utente['nome'] = $row['nome'];
			$utente['cognome'] = $row['cognome'];
			$utente['email'] = $row['email'];
			$utente['img'] = $row['imgProfilo'];
		}

		return $utente;
	}
}

?>