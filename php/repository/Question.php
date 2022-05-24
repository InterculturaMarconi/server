<?php
class Question
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function get($id)
    {
        $sql = "SELECT * FROM domande WHERE idDomanda = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":id" => $id]);

        $question = null;

        if ($row = $stmt->fetch()) {
            $question = [
                "id" => $id,
                "id_form" => $row["ksForm"],
                "testo" => $row["testo"],
                "tipo" => $row["tipo"],
            ];
        }

        return $question;
    }

    public function getByForm($id)
    {
        $sql = "SELECT * 
        FROM form 
        INNER JOIN domande 
            ON form.idForm = domande.ksForm 
        WHERE form.idForm = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":id" => $id]);

        $questions = [];

        while ($row = $stmt->fetch()) {
            $questions[] = [
                "id" => $row["idDomanda"],
                "id_form" => $row["ksForm"],
                "testo" => $row["testo"],
                "tipo" => $row["tipo"],
            ];
        }

        return $questions;
    }

    public function add($form, $testo, $tipo) {
        $sql = "INSERT INTO domande (ksForm, testo, tipo) 
            VALUES (:ksForm, :testo, :tipo)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":ksForm" => $form,
            ":testo" => $testo,
            ":tipo" => $tipo,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function update($id, $values) {
        $sql = "UPDATE domande SET";

        foreach ($values as $key => $value) {
            $sql .= " $key = :$key,";
        }

        $sql .= " WHERE idDomanda = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":id" => $id,
            ":testo" => $testo,
            ":tipo" => $tipo,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function delete($id) {
        $sql = "DELETE FROM domande WHERE idDomanda = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":id" => $id]);

        return $stmt->rowCount() > 0;
    }
}
