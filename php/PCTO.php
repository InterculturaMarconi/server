<?php

/**
 *
 */
class PCTO
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function login($email, $password)
    {
        $sql = "SELECT * FROM utenti WHERE email = :email AND password = :psw";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(
            array(
                ":email" => $email,
                ":psw" => md5($password),
            )
        );

        return $stmt->rowCount() > 0;
    }

    public function hasObjectivePermission($idObiettivo)
    {
        if ($this->isObjectiveAdmin($idObiettivo)) {
            return true;
        }

        $token = withAuth();
        $email = $token[0];

        $sql = "SELECT * FROM utenti
		INNER JOIN assegnazione
			ON utenti.idUtente = assegnazione.ksUtente
		INNER JOIN ruoli
			ON ruoli.idRuolo = assegnazione.ksRuolo
		WHERE utenti.email = :email AND ruoli.denominazione = 'obiettivo_:idObiettivo';";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":email" => $email,
                ":idObiettivo" => $idObiettivo,
            )
        );

        return $stmt->rowCount() > 0;
    }

    public function isObjectiveAdmin($idObiettivo)
    {
        if ($this->isAdmin()) {
            return true;
        }

        $token = withAuth();
        $email = $token[0];

        $sql = "SELECT * FROM utenti
		INNER JOIN assegnazione
			ON utenti.idUtente = assegnazione.ksUtente
		INNER JOIN ruoli
			ON ruoli.idRuolo = assegnazione.ksRuolo
		WHERE utenti.email = :email AND ruoli.denominazione = 'obiettivo_:idObiettivo_admin';";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":email" => $email,
                ":idObiettivo" => $idObiettivo,
            )
        );

        return $stmt->rowCount() > 0;
    }

    public function isAdmin()
    {
        $token = withAuth();
        $email = $token[0];

        $sql = "SELECT * FROM utenti
		INNER JOIN assegnazione
			ON utenti.idUtente = assegnazione.ksUtente
		INNER JOIN ruoli
			ON ruoli.idRuolo = assegnazione.ksRuolo
		WHERE utenti.email = :email AND ruoli.denominazione = 'admin';";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(":email" => $email)
        );

        return $stmt->rowCount() > 0;
    }

    public function isSelf($id)
    {
        $token = withAuth();
        $sql = "SELECT idUtente FROM utenti WHERE email = :email;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(
            array(
                ":email" => $token[0],
            )
        );

        $userId = $stmt->fetchColumn();

        return $id == $userId;
    }

    public function grant($user, $role)
    {
        $sql = "INSERT INTO assegnazione (ksUtente, ksRuolo) VALUES (:user, :role)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(
            array(
                ":user" => $user,
                ":role" => $role,
            )
        );

        return $stmt->rowCount() > 0;
    }

    public function revoke($user, $role)
    {
        $sql = "DELETE FROM assegnazione WHERE ksUtente = :user AND ksRuolo = :role";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(
            array(
                ":user" => $user,
                ":role" => $role,
            )
        );

        return $stmt->rowCount() > 0;
    }
}
