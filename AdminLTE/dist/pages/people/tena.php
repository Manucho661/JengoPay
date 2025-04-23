<?php
 include '../db/connect.php';
?>

<?php
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    // Capture POST data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['phone'] ?? '';

    // Insert query using prepared statement
    $sql = "INSERT INTO users (name, email, phone) VALUES (:name, :email, :phone)";
    $stmt = $conn->prepare($sql);

    try {
    $stmt->execute([
    ':name'    => $name,
    ':email'   => $email,
    ':phone' => $message
    ]);
    echo "✅ Data saved successfully!";
    } catch (PDOException $e) {
    echo "❌ Failed to save data: " . $e->getMessage();
    }

?>