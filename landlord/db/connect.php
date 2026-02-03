<?php
$host = '127.0.0.1';
$db   = 'bt_jengopay';
$user = 'root';
$pass = '';
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
