<?php
 include '../db/connect.php';
?>

<?php
try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  // Set error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Start transaction
  $conn->beginTransaction();

  // Step 1: Insert into users
  $sqlUser = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
  $stmtUser = $conn->prepare($sqlUser);
  $stmtUser->execute([
      ':name' => $_POST['name'],
      ':email' => $_POST['email'],
      ':password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
  ]);

  // Step 2: Get last inserted user_id
  $user_id = $conn->lastInsertId();

  // Step 3: Insert into tenants
  $sqlTenant = "INSERT INTO tenants (
      user_id, phone_number, apartment_number, lease_start_date,
      lease_end_date, rent_amount, status
  ) VALUES (
      :user_id, :phone, :apartment, :lease_start, :lease_end, :rent, :status
  )";

  $stmtTenant = $conn->prepare($sqlTenant);
  $stmtTenant->execute([
      ':user_id' => $user_id,
      ':phone' => $_POST['phone'],
      ':apartment' => $_POST['apartment'],
      ':lease_start' => $_POST['lease_start'],
      ':lease_end' => $_POST['lease_end'],
      ':rent' => $_POST['rent'],
      ':status' => 'active'
  ]);

  // Commit transaction
  $conn->commit();

  echo "✅ Tenant created successfully!";

} catch (PDOException $e) {
  $conn->rollBack();
  echo "❌ Error: " . $e->getMessage();
}

?>
