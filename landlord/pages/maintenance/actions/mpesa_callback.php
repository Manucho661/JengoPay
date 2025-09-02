<?php
$data = file_get_contents('php://input');
file_put_contents('mpesa_log.txt', $data, FILE_APPEND);
http_response_code(200);
?>
