<?php
$host = "";
$dbname = "bt_jengopay";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password,$dbname);

// Check connection
// if ($conn->connect_error) {
//     die("❌ Connection failed: " . $conn->connect_error);
// } else {
//     echo "✅ Connected successfully to the database.";
// }
?>
