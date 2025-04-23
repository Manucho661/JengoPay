<?php
$pdo = new PDO("mysql:host=localhost;dbname=bt_jengopay", "root", "");
$countyId = $_POST['county_id'];
$stmt = $pdo->prepare("SELECT id, name FROM constituencies WHERE county_id = ?");
$stmt->execute([$countyId]);

echo '<option value="">-- Select Constituency --</option>';
while ($row = $stmt->fetch()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
