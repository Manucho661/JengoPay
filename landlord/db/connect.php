<?php
$host = 'jobsphere.cbs4eusmuarq.eu-north-1.rds.amazonaws.com';
$db   = 'bt_jengopay';
$user = 'admin';
$pass = 'Manu1538';
$charset = 'utf8mb4';


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // VERY important
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
      // echo "Connected successfully"; // Optional
} catch (\PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
