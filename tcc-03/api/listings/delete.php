<?php

requireAuth();
$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Listing id is required.'], 400);
}

$stmt = db()->prepare('SELECT seller_id FROM listings WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$listing = $stmt->fetch();
if (!$listing || (int) $listing['seller_id'] !== (int) $_SESSION['user_id']) {
    sendJson(['success' => false, 'data' => null, 'message' => 'You can only delete your own listings.'], 403);
}

$delete = db()->prepare('UPDATE listings SET status = :status, updated_at = NOW() WHERE id = :id');
$delete->execute(['status' => 'removed', 'id' => $id]);

sendJson(['success' => true, 'data' => ['listing_id' => $id], 'message' => 'Listing removed successfully.'], 200);
