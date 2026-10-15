<?php
requireAuth();
$data = readJsonBody();
$gameId = (int) ($data['game_id'] ?? 0);
if ($gameId <= 0) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Game id is required.'], 400);
}

$stmt = db()->prepare('SELECT id FROM wishlists WHERE user_id = :user_id AND game_id = :game_id LIMIT 1');
$stmt->execute(['user_id' => $_SESSION['user_id'], 'game_id' => $gameId]);
if ($stmt->fetch()) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Game already in wishlist.'], 409);
}

$insert = db()->prepare('INSERT INTO wishlists (user_id, game_id, created_at) VALUES (:user_id, :game_id, NOW())');
$insert->execute(['user_id' => $_SESSION['user_id'], 'game_id' => $gameId]);

sendJson(['success' => true, 'data' => ['game_id' => $gameId], 'message' => 'Game added to wishlist.'], 201);
