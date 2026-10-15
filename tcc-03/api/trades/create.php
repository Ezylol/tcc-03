<?php
requireAuth();
$data = readJsonBody();
$offeredKeyId = (int) ($data['offered_key_id'] ?? 0);
$requestedListingId = (int) ($data['requested_listing_id'] ?? 0);
$message = trim((string) ($data['message'] ?? ''));

if ($offeredKeyId <= 0 || $requestedListingId <= 0) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Offered key and requested listing are required.'], 400);
}

$listingStmt = db()->prepare('SELECT l.*, g.title FROM listings l JOIN games g ON g.id = l.game_id WHERE l.id = :id AND l.status = :status LIMIT 1');
$listingStmt->execute(['id' => $requestedListingId, 'status' => 'active']);
$listing = $listingStmt->fetch();

if (!$listing) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Requested listing is not available.'], 404);
}

if ((int) $listing['seller_id'] === (int) $_SESSION['user_id']) {
    sendJson(['success' => false, 'data' => null, 'message' => 'You cannot trade with yourself.'], 409);
}

$ownerStmt = db()->prepare('SELECT owner_user_id, game_id FROM game_keys WHERE id = :id AND owner_user_id = :user_id LIMIT 1');
$ownerStmt->execute(['id' => $offeredKeyId, 'user_id' => $_SESSION['user_id']]);
$offeredKey = $ownerStmt->fetch();
if (!$offeredKey) {
    sendJson(['success' => false, 'data' => null, 'message' => 'The offered key does not belong to you.'], 403);
}

$duplicateStmt = db()->prepare('SELECT id FROM trades WHERE sender_user_id = :sender_user_id AND receiver_user_id = :receiver_user_id AND offered_key_id = :offered_key_id AND requested_listing_id = :requested_listing_id AND status IN (\'pending\', \'accepted\') LIMIT 1');
$duplicateStmt->execute([
    'sender_user_id' => $_SESSION['user_id'],
    'receiver_user_id' => $listing['seller_id'],
    'offered_key_id' => $offeredKeyId,
    'requested_listing_id' => $requestedListingId,
]);
if ($duplicateStmt->fetch()) {
    sendJson(['success' => false, 'data' => null, 'message' => 'A duplicate trade proposal already exists.'], 409);
}

if ((int) $offeredKey['game_id'] === (int) $listing['game_id']) {
    sendJson(['success' => false, 'data' => null, 'message' => 'You must offer a different game key for this trade.'], 400);
}

$insert = db()->prepare('INSERT INTO trades (sender_user_id, receiver_user_id, offered_key_id, requested_listing_id, message, status, created_at) VALUES (:sender_user_id, :receiver_user_id, :offered_key_id, :requested_listing_id, :message, :status, NOW())');
$insert->execute([
    'sender_user_id' => $_SESSION['user_id'],
    'receiver_user_id' => $listing['seller_id'],
    'offered_key_id' => $offeredKeyId,
    'requested_listing_id' => $requestedListingId,
    'message' => $message,
    'status' => 'pending',
]);

sendJson(['success' => true, 'data' => ['trade_id' => db()->lastInsertId()], 'message' => 'Trade proposal sent successfully.'], 201);
