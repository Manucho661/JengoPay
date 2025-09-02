<?php
require_once '../db/connect.php';

$id = $_GET['id'] ?? null;
$message = '';
$draft = null;

// Fetch draft
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id = ? AND status = 'Draft'");
    $stmt->execute([$id]);
    $draft = $stmt->fetch();
    if (!$draft) {
        $message = 'Draft not found or already sent/archived.';
    }
} else {
    $message = 'No draft ID provided.';
}

// Update draft
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $newMessage = $_POST['message'];
    $recipient = $_POST['recipient'];
    $priority  = $_POST['priority'];

    $stmt = $pdo->prepare("UPDATE announcements SET message = ?, recipient = ?, priority = ? WHERE id = ?");
    $stmt->execute([$newMessage, $recipient, $priority, $id]);

    header("Location: success.php"); // or show confirmation
    exit;
}
?>

<?php
// Example: Fetch buildings from database (if not already done)
require '../db/connect.php'; // Adjust path as needed

try {
  $stmt = $pdo->query("SELECT building_id, building_name FROM buildings ORDER BY building_name ASC");
  $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error fetching buildings: " . $e->getMessage();
  $buildings = [];
}
?>




<!DOCTYPE html>
<html>
<head>
  <title>Edit Draft</title>
  <style>
    :root {
      --primary: #00192D;
      --primary-light: #818cf8;
      --dark: #1e293b;
      --light: #f8fafc;
      --gray: #94a3b8;
      --gray-light: #e2e8f0;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --info: #3b82f6;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #f1f5f9;
      color: var(--dark);
      padding: 20px;
    }

    .form-container {
      max-width: 600px;
      margin: 2rem auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      overflow: hidden;
      padding: 2rem;
    }

    .form-header {
      color: var(--primary);
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--gray-light);
      font-size: 1.5rem;
      font-weight: 600;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--primary);
    }

    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid var(--gray-light);
      border-radius: 6px;
      font-size: 1rem;
      transition: all 0.3s;
      background-color: #f9f9f9;
    }

    .form-control:focus {
      border-color: #FFC107;
      box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
      outline: none;
      background-color: white;
    }

    textarea.form-control {
      min-height: 150px;
      resize: vertical;
    }

    .btn {
      padding: 0.75rem 1.5rem;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s ease;
      border: none;
      font-size: 1rem;
    }

    .btn-primary {
      background-color: #FFC107;
      color: #00192D;
      font-weight: 600;
    }

    .btn-primary:hover {
      background-color: #ffd54f;
      box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
      transform: translateY(-1px);
    }

    .message {
      padding: 1rem;
      margin-bottom: 1.5rem;
      border-radius: 6px;
      background-color: #fff8e1;
      color: #ff8f00;
      font-weight: 500;
    }

    /* Priority color indicators */
    option[value="Normal"] {
      color: #388e3c;
    }

    option[value="Urgent"] {
      color: #d32f2f;
      font-weight: bold;
    }

    option[value="Reminder"] {
      color: #ffa000;
    }

    .select-wrapper {
      position: relative;
    }

    .select-wrapper::after {
      content: "";
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      width: 0;
      height: 0;
      border-left: 6px solid transparent;
      border-right: 6px solid transparent;
      border-top: 6px solid var(--primary);
      pointer-events: none;
    }

    @media (max-width: 768px) {
      .form-container {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>

<div class="form-container">
  <?php if ($message): ?>
    <div class="message"><?= $message ?></div>
  <?php elseif ($draft): ?>
    <h2 class="form-header">Edit Draft Announcement</h2>
    <form method="POST">
      <div class="form-group">
      <label for="recipient">Select Recipient*</label>
          <select name="recipient" id="recipient" onchange="toggleShrink()" class="form-select recipient" required>
            <option value="" disabled selected>-- Select Building --</option>
              <option value="<?= htmlspecialchars($draft['building_name']) ?>">
                <?= htmlspecialchars($draft['building_name']) ?>
              </option>

          </select>
        <!-- <label for="recipient">Recipient</label> -->

      </div>

      <div class="form-group">
        <label for="priority">Priority</label>
        <div class="select-wrapper">
          <select id="priority" name="priority" class="form-control" required>
            <option value="Urgent" <?= $draft['priority'] === 'Urgent' ? 'selected' : '' ?>>Urgent</option>
            <option value="Normal" <?= $draft['priority'] === 'Normal' ? 'selected' : '' ?>>Normal</option>
            <option value="Reminder" <?= $draft['priority'] === 'Reminder' ? 'selected' : '' ?>>Reminder</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="message">Message</label>
        <textarea id="message" name="message" class="form-control" rows="5" required><?= htmlspecialchars($draft['message']) ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
  <?php endif; ?>
</div>

</body>
</html>
