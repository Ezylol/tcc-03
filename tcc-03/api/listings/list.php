<?php

$userId = getCurrentUserId();
$sql = 'SELECT l.*, g.title, g.genre, u.name as seller_name FROM listings l JOIN games g ON g.id = l.game_id JOIN users u ON u.id = l.seller_id WHERE 1 = 1';
$params = [];

if (!empty($_GET['status'])) {
    $status = trim((string) $_GET['status']);
    $sql .= ' AND l.status = :status';
    $params['status'] = $status;
} else {
    $sql .= ' AND l.status = :status';
    $params['status'] = 'active';
}

if (!empty($_GET['mine']) && $userId) {
    $sql .= ' AND l.seller_id = :seller_id';
    $params['seller_id'] = $userId;
}

$sql .= ' ORDER BY l.created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$listings = $stmt->fetchAll();

sendJson(['success' => true, 'data' => $listings, 'message' => 'Listings loaded successfully.'], 200);
