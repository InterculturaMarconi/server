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

	public function getuserInfo($email)
	{
		$utente = array();
		
		$sql = "SELECT * FROM utenti INNER JOIN assegnazione ON ksUtente = idUtente INNER JOIN ruoli ON ksRuolo = idRuolo WHERE email = '".$email."' ";

		$result = $this->getPdo()->query($sql);
		while($row = $result->fetch())
		{
			$utente['nome'] = $row['nome'];
			$utente['cognome'] = $row['cognome'];
			$utente['email'] = $row['email'];
			$utente['img'] = $row['imgProfilo'];
			$utente['idRuolo'] = $row['idRuolo'];
			$utente['denominazione'] = $row['denominazione'];

		}

		return $utente;
	}
}

?>