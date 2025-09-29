<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
	$conn = new PDO("mysql:host=$servername;dbname=bt_jengopay", $username, $password);
  // set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "DB Connected successfully";
} catch(PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
date_default_timezone_set('Africa/Nairobi');
$servetime = date('M, d Y H:i:s');
?>