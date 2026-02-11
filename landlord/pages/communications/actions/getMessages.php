<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // 1) Auth
    if (empty($_SESSION['user']['id'])) {
        throw new Exception("Not authenticated.");
    }

    $userId = (int) $_SESSION['user']['id'];

    /**
     * 2) Fetch messages for conversations where user is a participant
     * - We first find conversations via conversation_participants (cp_me)
     * - Then bring messages from conversation_messages (cm)
     * - Optionally bring sender details (users u)
     */
    $stmt = $pdo->prepare("
        SELECT
            c.id AS conversation_id,
            c.created_at AS conversation_created_at,

            cm.id AS message_id,
            cm.body,
            cm.created_at AS message_created_at,
            cm.sender_id AS message_sender_id,

            u.email
        FROM conversation_participants cp_me
        INNER JOIN conversations c
            ON c.id = cp_me.conversation_id
        INNER JOIN conversation_messages cm
            ON cm.conversation_id = c.id
        LEFT JOIN users u
            ON u.id = cm.sender_id
        WHERE cp_me.user_id = :user_id
        ORDER BY c.id DESC, cm.created_at ASC
    ");

    $stmt->execute([':user_id' => $userId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /**
     * 3) Group messages by conversation (no JSON, just PHP arrays)
     */
    $conversations = [];
    foreach ($rows as $r) {
        $cid = (int) $r['conversation_id'];

        if (!isset($conversations[$cid])) {
            $conversations[$cid] = [
                'conversation_id' => $cid,
                'conversation_created_at' => $r['conversation_created_at'],
                'messages' => []
            ];
        }

        $senderName = trim(
            ($r['first_name'] ?? '') . ' ' .
                ($r['middle_name'] ?? '') . ' ' .
                ($r['last_name'] ?? '')
        );

        $conversations[$cid]['messages'][] = [
            'message_id' => (int) $r['message_id'],
            'message' => $r['message'],
            'sender_id' => (int) $r['message_sender_id'],
            'sender_name' => $senderName !== '' ? $senderName : null,
            'created_at' => $r['message_created_at'],
        ];
    }

    // If you want a normal indexed array instead of conversation_id keys:
    $conversations = array_values($conversations);

    // Use $conversations in your view:
    // var_dump($conversations);

} catch (Throwable $e) {
    $_SESSION['error'] = 'Failed to load the messages: ' . $e->getMessage();
    error_log("Fetch conversations/messages error: " . $e->getMessage());
    http_response_code(500);
    // no json; just stop or show a normal message
    echo $e->getMessage();
}
