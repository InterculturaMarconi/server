<?php

    class User {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function get($id) {
            $sql = "SELECT * FROM utenti WHERE idUtente = :id";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute(
                array(
                    ":id" => $id
                )
            );


            $user = NULL;

            if ($row = $stmt->fetch()) {
                $user = array(
                    "id" => $id,
                    "nome" => $row['nome'],
                    "cognome" => $row['cognome'],
                    "email" => $row['email'],
                    "img" => $row['imgProfilo']
                );
            }

            return $user;
        }

        public function getAll() {
            $sql = "SELECT * FROM utenti";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $result = array();

            while($row = $stmt->fetch()) {
                $user = array(
                    "id" => $row['idUtente'],
                    "nome" => $row['nome'],
                    "cognome" => $row['cognome'],
                    "email" => $row['email'],
                    "img" => $row['imgProfilo']
                );

                array_push($result, $user);
            }

            return $result;
        }

        public function getByEmail($email) {
            $sql = "SELECT * FROM utenti WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute(
                array(
                    ":email" => $email
                )
            );

            $user = NULL;

            if ($row = $stmt->fetch()) {
                $user = array(
                    "id" => $row['idUtente'],
                    "nome" => $row['nome'],
                    "cognome" => $row['cognome'],
                    "email" => $row['email'],
                    "img" => $row['imgProfilo']
                );
            }

            return $user;
        }

        public function delete($id) {
            $sql = "DELETE FROM utenti WHERE idUtente = :id";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute(
                array(
                    ":id" => $id
                )
            );

            return $stmt->rowCount() > 0;
        }

        public function create($values) {
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
                    ":imgProfilo" => $values['imgProfilo']
                )
            );

            return $stmt->rowCount() > 0;
        }

        public function exists($id) {
            $sql = "SELECT * FROM utenti WHERE idUtente = :id";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute(
                array(
                    ":id" => $id
                )
            );

            return $stmt->rowCount() > 0;
        }

        public function existsByEmail($email) {
            $sql = "SELECT * FROM utenti WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute(
                array(
                    ":email" => $email
                )
            );

            return $stmt->rowCount() > 0;
        }

        // nome, cognome, email, password, imgProfilo
        public function update($id, $values = []){
            $currentValues = $this->get($id);

            if(key_exists("nome", $values)){
                $currentValues["nome"] = $values["nome"];
            }

            if(key_exists("cognome", $values)){
                $currentValues["cognome"] = $values["cognome"];
            }

            if(key_exists("email", $values)){
                $currentValues["email"] = $values["email"];
            }

            if(key_exists("password", $values)){
                $currentValues["password"] = $values["password"];
            }

            if(key_exists("imgProfilo", $values)){
                $currentValues["imgProfilo"] = $values["imgProfilo"];
            }

            $sql = "UPDATE utenti SET 
                nome = :nome, 
                cognome = :cognome,
                email = :email,
                password = :password,
                imgProfilo = :imgProfilo
                WHERE idUtente = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute(
                array(
                    ":nome" => $currentValues["nome"],
                    ":cognome" => $currentValues["cognome"],
                    ":email" => $currentValues["email"],
                    ":password" => $currentValues["password"],
                    ":imgProfilo" => $currentValues["imgProfilo"],
                    ":id" => $id
                )
            );

            return $stmt->rowCount() > 0;
        }
    }

?>