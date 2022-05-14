<?php
    class RESPONSE
    {
        private $status = 400;
        private $success = false;
        private $message = "";
        private $data = NULL;

        public function setStatus($status)
        {
            $this->status = $status;
        }

        public function setSuccess($success = true)
        {
            $this->success = $success;
        }

        public function setMessage($message)
        {
            $this->message = $message;
        }

        public function setData($data)
        {
            $this->data = $data;
        }

        public function send() {
            echo json_encode(array(
                "success" => $this->success,
                "message" => $this->message,
                "data" => $this->data
            ));
            http_response_code($this->status);
            exit();
        }
    }
?>