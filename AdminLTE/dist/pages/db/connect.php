<?php
$host = "";
$dbname = "bt_jengopay";
$username = "root";
$password = "";

<<<<<<< HEAD
// Create connection
$conn = new mysqli($host, $username, $password,$dbname);

// Check connection
// if ($conn->connect_error) {
//     die("❌ Connection failed: " . $conn->connect_error);
// } else {
//     echo "✅ Connected successfully to the database.";
// }
=======
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
>>>>>>> 8739e1337a1795548c47fa1a23eea29ef2ed8906
?>
