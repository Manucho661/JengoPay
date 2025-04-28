<?php
$host = "";
$dbname = "bt_jengopay";
$username = "root";
$password = "";

// Create connection
// $conn = new mysqli($host, $username, $password,$dbname);
try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  // Set error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}


// Check connection
//  if ($conn->connect_error) {
    //  die("❌ Connection failed: " . $conn->connect_error);
//  } else {
    //  echo "✅ Connected successfully to the database.";
//  }

?>
