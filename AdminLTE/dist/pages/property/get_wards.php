<?php
$pdo = new PDO("mysql:host=localhost;dbname=bt_jengopay", "root", "");
$constituencyId = $_POST['constituency_id'];
$stmt = $pdo->prepare("SELECT id, name FROM wards WHERE constituency_id = ?");
$stmt->execute([$constituencyId]);

echo '<option value="">-- Select Ward --</option>';
while ($row = $stmt->fetch()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
