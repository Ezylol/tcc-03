<?php

requireAuth();
$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Listing id is required.'], 400);
}

$data = readJsonBody();
$price = isset($data['price']) ? (float) $data['price'] : null;
$acceptsTrade = isset($data['accepts_trade']) ? (int) (bool) $data['accepts_trade'] : null;

$stmt = db()->prepare('SELECT seller_id FROM listings WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$listing = $stmt->fetch();
if (!$listing || (int) $listing['seller_id'] !== (int) $_SESSION['user_id']) {
    sendJson(['success' => false, 'data' => null, 'message' => 'You can only edit your own listings.'], 403);
}

$setParts = ['updated_at = NOW()'];
$values = ['id' => $id];
if ($price !== null) {
    $setParts[] = 'price = :price';
    $values['price'] = $price;
}
if ($acceptsTrade !== null) {
    $setParts[] = 'accepts_trade = :accepts_trade';
    $values['accepts_trade'] = $acceptsTrade;
}

$sql = 'UPDATE listings SET ' . implode(', ', $setParts) . ' WHERE id = :id';
$update = db()->prepare($sql);
$update->execute($values);

sendJson(['success' => true, 'data' => ['listing_id' => $id], 'message' => 'Listing updated successfully.'], 200);
