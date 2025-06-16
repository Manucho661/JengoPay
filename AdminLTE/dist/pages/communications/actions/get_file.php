<?php
include '../../db/connect.php'; // returns $pdo

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid file ID');
}

$id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT filename, mime_type, file_data FROM files WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$file) {
        die('File not found');
    }

    header("Content-Type: " . $file['mime_type']);
    header("Content-Disposition: attachment; filename=\"" . $file['filename'] . "\"");
    echo $file['file_data'];
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
