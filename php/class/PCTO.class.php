<?php

/**
 * 
 */
class PCTO extends DB
{
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

}

?>