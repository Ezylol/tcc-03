<?php
requireAuth();
$data = readJsonBody();
$listingId = (int) ($data['listing_id'] ?? 0);

if ($listingId <= 0) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Listing id is required.'], 400);
}

$db = db();
$db->beginTransaction();

$stmt = $db->prepare('SELECT l.*, g.title, g.id AS game_id FROM listings l JOIN games g ON g.id = l.game_id WHERE l.id = :id AND l.status = :status LIMIT 1');
$stmt->execute(['id' => $listingId, 'status' => 'active']);
$listing = $stmt->fetch();

if (!$listing) {
    $db->rollBack();
    sendJson(['success' => false, 'data' => null, 'message' => 'Listing is not available.'], 404);
}

if ((int) $listing['seller_id'] === (int) $_SESSION['user_id']) {
    $db->rollBack();
    sendJson(['success' => false, 'data' => null, 'message' => 'You cannot buy your own listing.'], 409);
}

$keyStmt = $db->prepare('SELECT id FROM game_keys WHERE game_id = :game_id AND owner_user_id = :owner_user_id AND status = :status ORDER BY id LIMIT 1');
$keyStmt->execute([
    'game_id' => $listing['game_id'],
    'owner_user_id' => $listing['seller_id'],
    'status' => 'available',
]);
$keyRow = $keyStmt->fetch();

if (!$keyRow) {
    $db->rollBack();
    sendJson(['success' => false, 'data' => null, 'message' => 'No available key for this listing.'], 409);
}

$keyId = (int) $keyRow['id'];

$assignKey = $db->prepare('UPDATE game_keys SET owner_user_id = :new_owner, status = :status, updated_at = NOW() WHERE id = :id');
$assignKey->execute([
    'new_owner' => $_SESSION['user_id'],
    'status' => 'sold',
    'id' => $keyId,
]);

$orderStmt = $db->prepare('INSERT INTO orders (buyer_id, seller_id, listing_id, key_id, total_price, status, created_at) VALUES (:buyer_id, :seller_id, :listing_id, :key_id, :total_price, :status, NOW())');
$orderStmt->execute([
    'buyer_id' => $_SESSION['user_id'],
    'seller_id' => $listing['seller_id'],
    'listing_id' => $listingId,
    'key_id' => $keyId,
    'total_price' => (float) $listing['price'],
    'status' => 'completed',
]);

$updateListing = $db->prepare('UPDATE listings SET status = :status, updated_at = NOW() WHERE id = :id');
$updateListing->execute(['status' => 'sold', 'id' => $listingId]);

$db->commit();

sendJson(['success' => true, 'data' => ['order_id' => $db->lastInsertId(), 'listing_id' => $listingId, 'key_id' => $keyId], 'message' => 'Purchase completed successfully.'], 201);
