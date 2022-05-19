<?php
    class Form {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function get($id) {
            $sql = "SELECT * FROM form WHERE idForm = :id";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute(
                array(
                    ":id" => $id
                )
            );

            $form = NULL;

            if ($row = $stmt->fetch()) {
                $form = array(
                    "idForm" => $id,
                    "dataVisualizzazione" => $row['dataVisualizzazione']
                );
            }

            return $form;
        }

        public function getByObiettivo($idObiettivo) {
            $sql = "SELECT * FROM form WHERE idObiettivo = :idObiettivo";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute(
                "idObiettivo" => $idObiettivo
            );

            $result = array();

            while($row = $stmt->fetch()) {
                $form = array(
                    "idForm" => $id,
                    "dataVisualizzazione" => $row['dataVisualizzazione']
                );

                array_push($result, $form);
            }

            return $result;
        }

        public function getAll() {
            $sql = "SELECT * FROM form";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $result = array();

            while($row = $stmt->fetch()) {
                $form = array(
                    "idForm" => $id,
                    "dataVisualizzazione" => $row['dataVisualizzazione']
                );

                array_push($result, $form);
            }

            return $result;
        }

        public function add($dataVisualizzazione, $idObiettivo){
            $sql = "INSERT INTO form SET dataVisualizzazione = :dataVisualizzazione, idObiettivo = :idObiettivo;";
            $stmt  = $this->pdo->prepare($sql);

            $stmt->execute(
                array(
                    "dataVisualizzazione" => $dataVisualizzazione,
                    "idObiettivo" => $idObiettivo
                )
            );
        }

        public function update($dataVisualizzazione, $idObiettivo){
            $sql = "UPDATE FROM form SET dataVisualizzazione = :dataVisualizzazione, idObiettivo = :idObiettivo;";
            $stmt  = $this->pdo->prepare($sql);

            $stmt->execute(
                array(
                    "dataVisualizzazione" => $dataVisualizzazione,
                    "idObiettivo" => $idObiettivo
                )
            );
        }

        public function delete($idObiettivo){
            $sql = "DELETE FROM form WHERE idObiettivo = :idObiettivo;";
            $stmt  = $this->pdo->prepare($sql);

            $stmt->execute(
                array(
                    "idObiettivo" => $idObiettivo
                )
            );
        }
    }
?>