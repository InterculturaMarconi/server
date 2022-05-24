<?php
class Answer
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function get($id)
    {
        $sql = "SELECT * FROM risposte WHERE idRisposta = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":id" => $id]);

        $risposta = null;

        if ($row = $stmt->fetch()) {
            $risposta = [
                "id_risposta" => $row["idRisposta"],
                "id_domanda" => $row["idDomanda"],
                "id_utente" => $row["idUtente"],
                "testo" => $row["testo"],
            ];
        }

        return $risposta;
    }

    public function getByUser($userId, $formId)
    {
        $sql = "SELECT risposte.*
        FROM form
        INNER JOIN domande
            ON ksForm = idForm
        INNER JOIN risposte
            ON ksDomanda = idDomanda
        WHERE idForm = :form AND idUtente = :user";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":form" => $formId, ":user" => $userId]);

        $risposte = [];

        while ($row = $stmt->fetch()) {
            $risposte = [
                "id_risposta" => $row["idRisposta"],
                "id_domanda" => $row["idDomanda"],
                "id_utente" => $row["idUtente"],
                "testo" => $row["testo"],
            ];
        }

        return $risposte;
    }

    public function getByForm($userId, $formId, $text)
    {
        $sql = "SELECT risposte.*
        FROM form
        INNER JOIN domande
            ON ksForm = idForm
        INNER JOIN risposte
            ON ksDomanda = idDomanda
        WHERE idForm = :form";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":form" => $formId]);

        $risposte = [];

        while ($row = $stmt->fetch()) {
            $risposte = [
                "id_risposta" => $row["idRisposta"],
                "id_domanda" => $row["idDomanda"],
                "id_utente" => $row["idUtente"],
                "testo" => $row["testo"],
            ];
        }

        return $risposte;
    }

    public function getByQuestion($id)
    {
        $sql = "SELECT * FROM risposte WHERE ksDomanda = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":id" => $id]);

        $risposte = [];

        while ($row = $stmt->fetch()) {
            $risposte = [
                "id_risposta" => $row["idRisposta"],
                "id_domanda" => $row["idDomanda"],
                "id_utente" => $row["idUtente"],
                "testo" => $row["testo"],
            ];
        }

        return $risposte;
    }

    public function add($userId, $questionId, $testo)
    {
        $sql = "INSERT INTO risposte (ksUtente, ksDomanda, testo) VALUES (:user, :question, :text)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":user" => $userId, ":question" => $questionId, ":text" => $testo]);

        return $stmt->rowCount() > 0;
    }
}
