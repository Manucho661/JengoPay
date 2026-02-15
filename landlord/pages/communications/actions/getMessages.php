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
    if (empty($_SESSION['user']['id'])) {
        throw new Exception("Not authenticated.");
    }

    $userId = (int) $_SESSION['user']['id'];

    $stmt = $pdo->prepare("
    SELECT
        c.id         AS conversation_id,
        c.title      AS conversation_title,
        c.created_by AS creator_user_id,
        c.created_at AS conversation_created_at,

        cu.email     AS creator_email,
        cu.role      AS creator_role,

        ct.first_name AS creator_tenant_first_name,
        cl.first_name AS creator_landlord_first_name,
        csp.name      AS creator_sp_name,

        -- All OTHER participants (exclude me) as: Name (role)
        COALESCE(
          GROUP_CONCAT(
            DISTINCT CONCAT(
              COALESCE(pt.first_name, pl.first_name, psp.name, pu.email, 'Unknown'),
              ' (', pu.role, ')'
            )
            ORDER BY pu.role ASC
            SEPARATOR ', '
          ),
          ''
        ) AS sent_to_display

    FROM conversations c

    -- Ensure I can see the conversation (creator OR participant)
    LEFT JOIN conversation_participants cp_me
      ON cp_me.conversation_id = c.id
     AND cp_me.user_id = :me1

    -- Creator user + profile
    LEFT JOIN users cu ON cu.id = c.created_by
    LEFT JOIN tenants ct ON ct.user_id = cu.id AND cu.role = 'tenant'
    LEFT JOIN landlords cl ON cl.user_id = cu.id AND cu.role = 'landlord'
    LEFT JOIN service_providers csp ON csp.user_id = cu.id AND cu.role = 'service_provider'

    -- All participants (excluding me) for SENT TO
    LEFT JOIN conversation_participants cp_other
      ON cp_other.conversation_id = c.id
     AND cp_other.user_id <> :me2

    LEFT JOIN users pu ON pu.id = cp_other.user_id
    LEFT JOIN tenants pt ON pt.user_id = pu.id AND pu.role = 'tenant'
    LEFT JOIN landlords pl ON pl.user_id = pu.id AND pu.role = 'landlord'
    LEFT JOIN service_providers psp ON psp.user_id = pu.id AND pu.role = 'service_provider'

    WHERE c.created_by = :me3
       OR cp_me.user_id IS NOT NULL

    GROUP BY c.id
    ORDER BY c.created_at DESC, c.id DESC
");

$stmt->execute([
  ':me1' => $userId,
  ':me2' => $userId,
  ':me3' => $userId,
]);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conversations = [];
foreach ($rows as $r) {
    $creatorName =
        $r['creator_tenant_first_name']
        ?? $r['creator_landlord_first_name']
        ?? $r['creator_sp_name']
        ?? null;

    $conversations[] = [
        'conversation_id' => (int)$r['conversation_id'],
        'title' => $r['conversation_title'],
        'created_at' => $r['conversation_created_at'],
        'created_by' => (int)$r['creator_user_id'],
        'creator' => [
            'user_id' => (int)$r['creator_user_id'],
            'email'   => $r['creator_email'] ?? null,
            'role'    => $r['creator_role'] ?? null,
            'name'    => $creatorName,
        ],
        'sent_to_display' => (string)($r['sent_to_display'] ?? ''),
    ];
}


} catch (Throwable $e) {
    $_SESSION['error'] = 'Failed to load the conversations: ' . $e->getMessage();
    error_log("Fetch conversations error: " . $e->getMessage());
    http_response_code(500);
    echo $e->getMessage();
}
