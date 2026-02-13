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

    // 2) Fetch messages for conversations where user is a participant
    $stmt = $pdo->prepare("
        SELECT
            c.id AS conversation_id,
            c.created_at AS conversation_created_at,

            cm.id AS message_id,
            cm.body AS message_body,
            cm.created_at AS message_created_at,
            cm.sender_id AS message_sender_id,

            u.email,
            u.role,

            t.first_name AS tenant_first_name,
            l.first_name AS landlord_first_name,
            sp.name      AS sp_first_name  -- change to sp.first_name if that's your column

        FROM conversation_participants cp_me
        INNER JOIN conversations c
            ON c.id = cp_me.conversation_id
        INNER JOIN conversation_messages cm
            ON cm.conversation_id = c.id
        LEFT JOIN users u
            ON u.id = cm.sender_id

        LEFT JOIN tenants t
            ON t.user_id = u.id AND u.role = 'tenant'
        LEFT JOIN landlords l
            ON l.user_id = u.id AND u.role = 'landlord'
        LEFT JOIN service_providers sp
            ON sp.user_id = u.id AND u.role = 'service_provider'

        WHERE cp_me.user_id = :user_id
        ORDER BY c.id DESC, cm.created_at ASC
    ");

    $stmt->execute([':user_id' => $userId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3) Group messages by conversation
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

        $senderName =
            $r['tenant_first_name']
            ?? $r['landlord_first_name']
            ?? $r['sp_first_name']
            ?? null;

        $conversations[$cid]['messages'][] = [
            'message_id' => (int) $r['message_id'],
            'message' => $r['message_body'],
            'sender_id' => (int) $r['message_sender_id'],
            'sender_email' => $r['email'] ?? null,
            'sender_role' => $r['role'] ?? null,
            'sender_name' => $senderName, // already null or string
            'created_at' => $r['message_created_at'],
        ];
    }

} catch (Throwable $e) {
    $_SESSION['error'] = 'Failed to load the messages: ' . $e->getMessage();
    error_log("Fetch conversations/messages error: " . $e->getMessage());
    http_response_code(500);
    echo $e->getMessage();
}
