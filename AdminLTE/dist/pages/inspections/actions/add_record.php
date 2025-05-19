<?php
include '../../db/connect.php';

$items = ['window', 'floor', 'socket'];
foreach ($items as $item) {
        $status = $_POST["{$item}_condition"];
        $desc   = isset($_POST["{$item}_description"]) ? $_POST["{$item}_description"] : null;

        // Insert into inspection_items
        $stmt = $pdo->prepare("
            INSERT INTO inspection_items (inspection_id, category, status, description)
            VALUES (:inspection_id, :category, :status, :description)
        ");
        $stmt->execute([
            'inspection_id' => $inspection_id,
            'category'      => ucfirst($item),
            'status'        => $status,
            'description'   => $status === 'Needs Repair' ? $desc : null
        ]);
    }
?>