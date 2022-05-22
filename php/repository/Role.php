<?php
class Role
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function get($id)
    {
        $sql = "SELECT * FROM ruoli WHERE idRuolo = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":id" => $id,
            )
        );

        $role = null;

        if ($row = $stmt->fetch()) {
            $role = array(
                "id" => $id,
                "nome" => $row['nome'],
                "descrizione" => $row['descrizione'],
            );
        }

        return $role;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM ruoli";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        $result = array();

        while ($row = $stmt->fetch()) {
            $role = array(
                "id" => $row['idRuolo'],
                "nome" => $row['nome'],
                "descrizione" => $row['descrizione'],
            );

            array_push($result, $role);
        }

        return $result;
    }

    public function getByName($name)
    {
        $sql = "SELECT * FROM ruoli WHERE nome = :name";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":name" => $name,
            )
        );

        $role = null;

        if ($row = $stmt->fetch()) {
            $role = array(
                "id" => $row['idRuolo'],
                "nome" => $row['nome'],
                "descrizione" => $row['descrizione'],
            );
        }

        return $role;
    }

    public function getByUser($id)
    {
        $sql = "SELECT ruoli.idRuolo, ruoli.denominazione
            FROM assegnazione
            INNER JOIN ruoli
                ON assegnazione.ksRuolo = ruoli.idRuolo
            WHERE assegnazione.ksUtente = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":id" => $id,
            )
        );

        $roles = array();

        while ($row = $stmt->fetch()) {
            $role = array(
                "id" => $row['idRuolo'],
                "denominazione" => $row['denominazione'],
            );

            array_push($roles, $role);
        }

        return $roles;
    }
}
