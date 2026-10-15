<?php

requireAuth();
$data = readJsonBody();
$gameId = (int) ($data['game_id'] ?? 0);
$price = (float) ($data['price'] ?? 0);
$listingType = trim((string) ($data['listing_type'] ?? 'sale'));
$acceptsTrade = !empty($data['accepts_trade']) ? 1 : 0;

if ($gameId <= 0 || $price < 0) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Invalid game or price.'], 400);
}

$stmt = db()->prepare('INSERT INTO listings (seller_id, game_id, listing_type, price, accepts_trade, status, created_at) VALUES (:seller_id, :game_id, :listing_type, :price, :accepts_trade, :status, NOW())');
$stmt->execute([
    'seller_id' => $_SESSION['user_id'],
    'game_id' => $gameId,
    'listing_type' => $listingType,
    'price' => $price,
    'accepts_trade' => $acceptsTrade,
    'status' => 'active',
]);

sendJson(['success' => true, 'data' => ['listing_id' => db()->lastInsertId()], 'message' => 'Listing created successfully.'], 201);
