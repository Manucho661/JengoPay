// callback.php
<?php
$callbackResponse = file_get_contents('php://input');
file_put_contents('mpesa_callback.json', $callbackResponse); // Save for testing

// Process or store in database as needed
