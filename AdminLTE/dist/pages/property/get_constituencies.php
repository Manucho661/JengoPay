<?php
$conn = new mysqli("localhost", "root", "", "bt_jengopay");
$id = intval($_POST['id']);
$query = $conn->query("SELECT id, name FROM constituencies WHERE id = $id");
echo '<option value="">-- Select Constituency --</option>';
while ($row = $query->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
