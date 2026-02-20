<?php
require_once '../../db/connect.php'; // PDO connection in $pdo
session_start();

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

function redirect_with_message(string $url, string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
    header("Location: $url");
    exit;
}

/* --------------------------------------------------
   Only accept POST submit
-------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['vacate_tenant'])) {
    return;
}

try {

    /* --------------------------------------------------
       Validate tenancy_id
    -------------------------------------------------- */
    $tenancyId = $_POST['tenancy_id'] ?? null;

    if (!$tenancyId || !ctype_digit($tenancyId)) {
        throw new Exception("Invalid tenancy ID.");
    }

    /* --------------------------------------------------
       Update tenancy status
    -------------------------------------------------- */
    $stmt = $pdo->prepare("
        UPDATE tenancies
        SET status = 'Vacated',
            updated_at = NOW()
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$tenancyId]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("No tenancy updated.");
    }

    redirect_with_message($_SERVER['REQUEST_URI'], 'success', 'Tenant vacated successfully.');

} catch (Throwable $e) {

    redirect_with_message(
        $_SERVER['REQUEST_URI'],
        'danger',
        'Failed to vacate tenant: ' . $e->getMessage()
    );
}
