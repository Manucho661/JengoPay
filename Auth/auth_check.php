<?php

session_start();

// Check if the user is logged in and if the role is 'landlord'
if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] !== 'landlord') {
    header("Location: ../../../auth/login.php");
    exit;
}
