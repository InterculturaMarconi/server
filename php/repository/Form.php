<?php
class Form
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function get($id)
    {
        $sql = "SELECT * FROM form WHERE idForm = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":id" => $id,
            )
        );

        $form = null;

        if ($row = $stmt->fetch()) {
            $form = array(
                "id" => $id,
                "visibile_il" => $row["dataVisualizzazione"],
                "id_obiettivo" => $row["idObiettivo"],
            );
        }

        return $form;
    }

    public function getByObiettivo($idObiettivo)
    {
        $sql = "SELECT * FROM form WHERE idObiettivo = :idObiettivo";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":idObiettivo" => $idObiettivo,
            )
        );

        $result = array();

        while ($row = $stmt->fetch()) {
            $form = array(
                "id" => $row["idForm"],
                "visibile_il" => $row["dataVisualizzazione"],
                "id_obiettivo" => $idObiettivo,
            );

            array_push($result, $form);
        }

        return $result;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM form";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        $result = array();

        while ($row = $stmt->fetch()) {
            $form = array(
                "id" => $row["idForm"],
                "visibile_il" => $row["dataVisualizzazione"],
                "id_obiettivo" => $row["idObiettivo"],
            );

            array_push($result, $form);
        }

        return $result;
    }

    public function getVisulizzabile($idObiettivo)
    {
        $sql = "SELECT * FROM form WHERE idObiettivo = :idObiettivo AND DATEDIFF(CURRENT_DATE, dataVisualizzazione) >= 0 ORDER BY dataVisualizzazione DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":idObiettivo" => $idObiettivo,
            )
        );

        $form = null;

        if ($row = $stmt->fetch()) {
            $form = array(
                "id" => $row["idForm"],
                "visibile_il" => $row["dataVisualizzazione"],
                "id_obiettivo" => $idObiettivo,
            );
        }

        return $form;
    }

    public function add($dataVisualizzazione, $idObiettivo)
    {
        $sql = "INSERT INTO form (dataVisualizzazione, idObiettivo) VALUES (:dataVisualizzazione, :idObiettivo);";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":dataVisualizzazione" => $dataVisualizzazione,
                ":idObiettivo" => $idObiettivo,
            )
        );

        return $stmt->rowCount() > 0;
    }

    public function update($dataVisualizzazione, $idObiettivo)
    {
        $sql = "UPDATE FROM form SET dataVisualizzazione = :dataVisualizzazione, idObiettivo = :idObiettivo;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":dataVisualizzazione" => $dataVisualizzazione,
                ":idObiettivo" => $idObiettivo,
            )
        );

        return $stmt->rowCount() > 0;
    }

    public function delete($idObiettivo)
    {
        $sql = "DELETE FROM form WHERE idObiettivo = :idObiettivo;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(
            array(
                ":idObiettivo" => $idObiettivo,
            )
        );

        return $stmt->rowCount() > 0;
    }
}
