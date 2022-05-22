<?php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function get($id)
    {
        $sql = "SELECT * FROM utenti WHERE idUtente = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":id" => $id,
            )
        );

        $user = null;

        if ($row = $stmt->fetch()) {
            $user = array(
                "id" => $id,
                "nome" => $row['nome'],
                "cognome" => $row['cognome'],
                "email" => $row['email'],
                "img" => $row['imgProfilo'],
            );
        }

        return $user;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM utenti";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        $result = array();

        while ($row = $stmt->fetch()) {
            $user = array(
                "id" => $row['idUtente'],
                "nome" => $row['nome'],
                "cognome" => $row['cognome'],
                "email" => $row['email'],
                "img" => $row['imgProfilo'],
            );

            array_push($result, $user);
        }

        return $result;
    }

    public function getByEmail($email)
    {
        $sql = "SELECT * FROM utenti WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":email" => $email,
            )
        );

        $user = null;

        if ($row = $stmt->fetch()) {
            $user = array(
                "id" => $row['idUtente'],
                "nome" => $row['nome'],
                "cognome" => $row['cognome'],
                "email" => $row['email'],
                "img" => $row['imgProfilo'],
            );
        }

        return $user;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM utenti WHERE idUtente = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":id" => $id,
            )
        );

        return $stmt->rowCount() > 0;
    }

    public function create($values)
    {
        $sql = "INSERT INTO utenti (nome, cognome, email, password, imgProfilo) VALUES (:nome, :cognome, :email, :password, :imgProfilo)";
        $stmt = $this->pdo->prepare($sql);

        if (
            !key_exists("nome", $values) ||
            !key_exists("cognome", $values) ||
            !key_exists("email", $values) ||
            !key_exists("password", $values) ||
            !key_exists("imgProfilo", $values)
        ) {
            return false;
        }

        $stmt->execute(
            array(
                ":nome" => $values['nome'],
                ":cognome" => $values['cognome'],
                ":email" => $values['email'],
                ":password" => $values['password'],
                ":imgProfilo" => $values['imgProfilo'],
            )
        );

        return $stmt->rowCount() > 0;
    }

    public function exists($id)
    {
        $sql = "SELECT * FROM utenti WHERE idUtente = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":id" => $id,
            )
        );

        return $stmt->rowCount() > 0;
    }

    public function existsByEmail($email)
    {
        $sql = "SELECT * FROM utenti WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":email" => $email,
            )
        );

        return $stmt->rowCount() > 0;
    }

    // nome, cognome, email, password, imgProfilo
    public function update($id, $values = [])
    {
        $sql = "UPDATE utenti SET ";
        foreach ($values as $key => $value) {
            $sql .= $key . " = :" . $key;

            if (count($values) > 1) {
                $sql .= ", ";
            }
        }

        $sql .= " WHERE idUtente = :id;";
        $stmt = $this->pdo->prepare($sql);

        foreach ($values as $key => $value) {
            $stmt->bindValue(":" . $key, $value);
        }

        var_dump($sql);

        $stmt->bindValue(":id", $id);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
