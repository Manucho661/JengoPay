<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Auth
    if (empty($_SESSION['user']['id'])) {
        throw new Exception("Not authenticated.");
    }
    $userId = (int)$_SESSION['user']['id'];

    /* =========================================================
       1) TOTAL UNREAD MESSAGES (across ALL conversations)
    ========================================================= */
    $sqlTotalUnread = "
        SELECT COUNT(*) AS total_unread
        FROM conversation_messages cm
        INNER JOIN conversation_participants cp
            ON cp.conversation_id = cm.conversation_id
        WHERE cp.user_id = :user_id
          AND cm.sender_id <> :user_id
          AND cm.id > COALESCE(cp.last_read_message_id, 0)
    ";
    $stmtTotal = $pdo->prepare($sqlTotalUnread);
    $stmtTotal->execute([':user_id' => $userId]);
    $totalUnreadMessages = (int)($stmtTotal->fetchColumn() ?? 0);

    /* =========================================================
       2) CONVERSATIONS WITH UNREAD MESSAGES (count per conversation)
       - only selects columns from conversations + unread_count
       - no message bodies
    ========================================================= */
    $sqlUnreadConversations = "
        SELECT
            c.id AS conversation_id,
            c.title,
            c.created_by,
            c.created_at,
            COUNT(cm.id) AS unread_count
        FROM conversation_participants cp
        INNER JOIN conversations c
            ON c.id = cp.conversation_id
        INNER JOIN conversation_messages cm
            ON cm.conversation_id = c.id
           AND cm.sender_id <> :user_id
           AND cm.id > COALESCE(cp.last_read_message_id, 0)
        WHERE cp.user_id = :user_id
        GROUP BY c.id, c.title, c.created_by, c.created_at
        ORDER BY MAX(cm.id) DESC
    ";
    $stmtUnread = $pdo->prepare($sqlUnreadConversations);
    $stmtUnread->execute([':user_id' => $userId]);
    $unreadConversations = $stmtUnread->fetchAll(PDO::FETCH_ASSOC);

    // Now you have:
    // $totalUnreadMessages (int)
    // $unreadConversations (array of conversations each with unread_count)

} catch (Throwable $e) {
    $_SESSION['error'] = "Failed to load unread messages: " . $e->getMessage();
    error_log("Unread messages error: " . $e->getMessage());
    $totalUnreadMessages = 0;
    $unreadConversations = [];
}
