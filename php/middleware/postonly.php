<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(array('error' => 'Invalid request method. Use POST instead.'));
        http_response_code(405);
        exit();
    }
?>