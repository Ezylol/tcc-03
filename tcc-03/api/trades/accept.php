<?php
requireAuth();
$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Trade id is required.'], 400);
}

$db = db();
$db->beginTransaction();

$stmt = $db->prepare('SELECT * FROM trades WHERE id = :id AND receiver_user_id = :user_id AND status = :status LIMIT 1');
$stmt->execute(['id' => $id, 'user_id' => $_SESSION['user_id'], 'status' => 'pending']);
$trade = $stmt->fetch();
if (!$trade) {
    $db->rollBack();
    sendJson(['success' => false, 'data' => null, 'message' => 'Trade not available to accept.'], 404);
}

$checkOffered = $db->prepare('SELECT id, owner_user_id FROM game_keys WHERE id = :id AND owner_user_id = :owner_user_id LIMIT 1');
$checkOffered->execute(['id' => $trade['offered_key_id'], 'owner_user_id' => $trade['sender_user_id']]);
if (!$checkOffered->fetch()) {
    $db->rollBack();
    sendJson(['success' => false, 'data' => null, 'message' => 'The offered key is no longer available for trade.'], 409);
}

$requestedListing = $db->prepare('SELECT seller_id, game_id FROM listings WHERE id = :id AND status = :status LIMIT 1');
$requestedListing->execute(['id' => $trade['requested_listing_id'], 'status' => 'active']);
$listing = $requestedListing->fetch();
if (!$listing) {
    $db->rollBack();
    sendJson(['success' => false, 'data' => null, 'message' => 'Requested listing is no longer active.'], 409);
}

$requestedKey = $db->prepare('SELECT id FROM game_keys WHERE game_id = :game_id AND owner_user_id = :owner_id AND status = :status ORDER BY id LIMIT 1');
$requestedKey->execute(['game_id' => $listing['game_id'], 'owner_id' => $listing['seller_id'], 'status' => 'available']);
$requestedKeyRow = $requestedKey->fetch();

$updateTrade = $db->prepare('UPDATE trades SET status = :status, updated_at = NOW() WHERE id = :id');
$updateTrade->execute(['status' => 'accepted', 'id' => $id]);

$updateOffered = $db->prepare('UPDATE game_keys SET owner_user_id = :new_owner, status = :status WHERE id = :key_id');
$updateOffered->execute(['new_owner' => $trade['receiver_user_id'], 'status' => 'traded', 'key_id' => $trade['offered_key_id']]);

if ($requestedKeyRow) {
    $db->prepare('UPDATE game_keys SET owner_user_id = :new_owner, status = :status WHERE id = :key_id')->execute([
        'new_owner' => $trade['sender_user_id'],
        'status' => 'traded',
        'key_id' => $requestedKeyRow['id'],
    ]);
}

$db->prepare('UPDATE listings SET status = :status, updated_at = NOW() WHERE id = :id')->execute(['status' => 'sold', 'id' => $trade['requested_listing_id']]);
$db->commit();

sendJson(['success' => true, 'data' => ['trade_id' => $id], 'message' => 'Trade accepted successfully.'], 200);
