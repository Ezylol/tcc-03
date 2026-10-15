<?php
requireAuth();
$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Trade id is required.'], 400);
}

$stmt = db()->prepare('SELECT id FROM trades WHERE id = :id AND receiver_user_id = :user_id AND status = :status LIMIT 1');
$stmt->execute(['id' => $id, 'user_id' => $_SESSION['user_id'], 'status' => 'pending']);
if (!$stmt->fetch()) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Trade not available to reject.'], 404);
}

$update = db()->prepare('UPDATE trades SET status = :status, updated_at = NOW() WHERE id = :id');
$update->execute(['status' => 'rejected', 'id' => $id]);

sendJson(['success' => true, 'data' => ['trade_id' => $id], 'message' => 'Trade rejected successfully.'], 200);
