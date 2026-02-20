<?php
require_once '../../db/connect.php'; // PDO connection in $pdo

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});



function redirect_with_message(string $url, string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,      // success | danger | warning | info
        'message' => $message
    ];
    header("Location: $url");
    exit;
}

// Only accept POST submit
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['submitRequest'])) {
    return; // do nothing on GET
}


try {



    $_SESSION['success'] =
        "Maintenance request submitted successfully.";

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;

    exit;
} catch (Throwable $e) {
    $_SESSION['error'] =
        'Failed to submit maintenance request: ' . $e->getMessage();

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
