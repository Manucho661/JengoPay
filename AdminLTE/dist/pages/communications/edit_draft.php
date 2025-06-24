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

<!DOCTYPE html>
<html>
<head>
  <title>Edit Draft</title>
  <style>
    form { max-width: 500px; margin: 30px auto; }
    label, textarea, input, select { display: block; width: 100%; margin-bottom: 15px; }
  </style>
</head>
<body>

<?php if ($message): ?>
  <p><?= $message ?></p>
<?php elseif ($draft): ?>
  <h2>Edit Draft</h2>
  <form method="POST">
    <label>Recipient</label>
    <input type="text" name="recipient" value="<?= htmlspecialchars($draft['recipient']) ?>" required>

    <label>Priority</label>
    <select name="priority" required>
      <option value="Urgent" <?= $draft['priority'] === 'Urgent' ? 'selected' : '' ?>>Urgent</option>
      <option value="Normal" <?= $draft['priority'] === 'Normal' ? 'selected' : '' ?>>Normal</option>
      <option value="Reminder" <?= $draft['priority'] === 'Reminder' ? 'selected' : '' ?>>Reminder</option>
    </select>

    <label>Message</label>
    <textarea name="message" rows="5" required><?= htmlspecialchars($draft['message']) ?></textarea>

    <button type="submit">Save Changes</button>
  </form>
<?php endif; ?>

</body>
</html>
