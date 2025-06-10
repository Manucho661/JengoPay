<?php
require 'db_connection.php'; // Your PDO connection
$buildingId = $_GET['building_id'] ?? '';

if (!$buildingId) {
  echo '<tr><td colspan="5" class="text-center">No building selected.</td></tr>';
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM communications WHERE building_id = :building_id ORDER BY created_at DESC");
$stmt->execute(['building_id' => $buildingId]);
$communications = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$communications) {
  echo '<tr><td colspan="5" class="text-center">No messages found for this building.</td></tr>';
  exit;
}

foreach ($communications as $comm):
    $datetime = new DateTime($comm['created_at']);
    $date = $datetime->format('d-m-Y');
    $time = $datetime->format('h:iA');
    $sender = htmlspecialchars($comm['tenant'] ?: 'Tenant');
    $recipient = htmlspecialchars($comm['recipient'] ?? 'Sender Name');
    $title = htmlspecialchars($comm['title']);
    $threadId = $comm['thread_id'];
?>
<tr class="table-row">
  <td class="timestamp">
    <div class="date"><?= $date ?></div>
    <div class="time"><?= $time ?></div>
  </td>
  <td class="title"><?= $title ?></td>
  <td><div class="recipient"><?= $recipient ?></div></td>
  <td>
    <div class="sender"><?= $sender ?></div>
    <div class="sender-email"></div>
  </td>
  <td>
    <button class="btn btn-primary view" onclick="loadConversation(<?= $threadId ?>)">
      <i class="bi bi-eye"></i> View
    </button>
    <button class="btn btn-danger delete" data-thread-id="<?= $threadId ?>">
      <i class="bi bi-trash3"></i> Delete
    </button>
  </td>
</tr>
<?php endforeach; ?>
