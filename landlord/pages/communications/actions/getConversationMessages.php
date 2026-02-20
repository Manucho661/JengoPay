<?php
declare(strict_types=1);

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/connect.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    if (empty($_SESSION['user']['id'])) {
        throw new Exception("Not authenticated.");
    }
    $meId = (int)$_SESSION['user']['id'];

    $conversationId = isset($_GET['conversation_id']) ? (int)$_GET['conversation_id'] : 0;
    if ($conversationId <= 0) {
        throw new Exception("Missing/invalid conversation_id.");
    }

    // ---------------------------------------------------------
    // 1) AuthZ: ensure I am a participant in this conversation
    // ---------------------------------------------------------
    $stmt = $pdo->prepare("
        SELECT 1
        FROM conversation_participants
        WHERE conversation_id = :cid AND user_id = :me
        LIMIT 1
    ");
    $stmt->execute([':cid' => $conversationId, ':me' => $meId]);
    if (!$stmt->fetchColumn()) {
        throw new Exception("Access denied.");
    }

    // ---------------------------------------------------------
    // 2) Get OTHER participant user_id (the person I'm chatting with)
    // ---------------------------------------------------------
    $stmt = $pdo->prepare("
        SELECT user_id
        FROM conversation_participants
        WHERE conversation_id = :cid AND user_id <> :me
        LIMIT 1
    ");
    $stmt->execute([':cid' => $conversationId, ':me' => $meId]);
    $otherUserId = (int)($stmt->fetchColumn() ?: 0);

    // If it's a self-chat or group chat, this may be 0 (handle gracefully)
    $contact = [
        'user_id' => $otherUserId ?: null,
        'role'    => null,
        'name'    => null,
        'email'   => null,
    ];

    if ($otherUserId > 0) {
        // ---------------------------------------------------------
        // 3) Resolve OTHER participant details based on role
        // ---------------------------------------------------------
        $stmt = $pdo->prepare("SELECT id, email, role FROM users WHERE id = :uid LIMIT 1");
        $stmt->execute([':uid' => $otherUserId]);
        $u = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($u) {
            $contact['user_id'] = (int)$u['id'];
            $contact['role']    = $u['role'] ?? null;
            $contact['email']   = $u['email'] ?? null;

            if (($u['role'] ?? '') === 'tenant') {
                $stmt = $pdo->prepare("
                    SELECT first_name, middle_name
                    FROM tenants
                    WHERE user_id = :uid
                    LIMIT 1
                ");
                $stmt->execute([':uid' => $otherUserId]);
                $t = $stmt->fetch(PDO::FETCH_ASSOC);

                $first = trim((string)($t['first_name'] ?? ''));
                $mid   = trim((string)($t['middle_name'] ?? ''));
                $contact['name'] = trim($first . ' ' . $mid) ?: ($contact['email'] ?? 'Tenant');

            } elseif (($u['role'] ?? '') === 'service_provider') {
                $stmt = $pdo->prepare("
                    SELECT name
                    FROM service_providers
                    WHERE user_id = :uid
                    LIMIT 1
                ");
                $stmt->execute([':uid' => $otherUserId]);
                $spName = $stmt->fetchColumn();
                $contact['name'] = $spName ?: ($contact['email'] ?? 'Service Provider');

            } else {
                // fallback
                $contact['name'] = $contact['email'] ?? 'User';
            }
        }
    }

    // ---------------------------------------------------------
    // 4) Get building/unit link info (conversation_links)
    // ---------------------------------------------------------
    $stmt = $pdo->prepare("
        SELECT
            cl.building_id,
            cl.unit_id,
            b.building_name,
            bu.unit_number
        FROM conversation_links cl
        LEFT JOIN buildings b ON b.id = cl.building_id
        LEFT JOIN building_units bu ON bu.id = cl.unit_id
        WHERE cl.conversation_id = :cid
        LIMIT 1
    ");
    $stmt->execute([':cid' => $conversationId]);
    $link = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    $context = [
        'building_id'   => isset($link['building_id']) ? (int)$link['building_id'] : null,
        'unit_id'       => isset($link['unit_id']) ? (int)$link['unit_id'] : null,
        'building_name' => $link['building_name'] ?? null,
        'unit_number'   => $link['unit_number'] ?? null,
    ];

    // ---------------------------------------------------------
    // 5) Load messages
    // ---------------------------------------------------------
    $stmt = $pdo->prepare("
        SELECT
            id,
            conversation_id,
            sender_id,
            body,
            created_at
        FROM conversation_messages
        WHERE conversation_id = :cid
        ORDER BY created_at ASC, id ASC
    ");
    $stmt->execute([':cid' => $conversationId]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'ok' => true,
        'me_id' => $meId,
        'conversation' => [
            'id' => $conversationId,
        ],
        'contact' => $contact,
        'context' => $context,
        'messages' => array_map(function ($m) {
            return [
                'id' => (int)$m['id'],
                'conversation_id' => (int)$m['conversation_id'],
                'sender_id' => (int)$m['sender_id'],
                'body' => $m['body'],
                'created_at' => $m['created_at'],
            ];
        }, $messages),
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => $e->getMessage(),
    ]);
    exit;
}