<?php
    class RESPONSE
    {
        private int $status = 400;
        private $success = false;
        private string $message = "";
        private array $data = array();
        private int $error = -1;

        public function setStatus(int $status)
        {
            $this->status = $status;
        }

        public function setSuccess($success = true)
        {
            $this->success = $success;
        }

        public function setMessage(string $message)
        {
            $this->message = $message;
        }

        public function setData(array $data)
        {
            $this->data = $data;
        }

        public function setError(int $error)
        {
            $this->error = $error;
        }

        public function send() {
            echo json_encode(array(
                "success" => $this->success,
                "message" => $this->message,
                "data" => $this->data,
                "error" => $this->error
            ));

            http_response_code($this->status);

            exit();
        }
    }
?>