<?php
$connection = new mysqli("localhost", "root", "", "bt_jengopay");
$constituency_id = intval($_POST['constituency_id']);
$query = $conn->query("SELECT id, name FROM wards WHERE constituency_id = $constituency_id");
echo '<option value="">-- Select Ward --</option>';
while ($row = $query->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
