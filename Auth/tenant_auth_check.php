<?php
// Check if the user is logged in and if the role is 'landlord'
if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] !== 'tenant') {
    header("Location: /jengopay/auth/login.php");
    exit;
}
