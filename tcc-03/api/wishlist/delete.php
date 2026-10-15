<?php
requireAuth();
$data = readJsonBody();
$gameId = (int) ($data['game_id'] ?? 0);
if ($gameId <= 0) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Game id is required.'], 400);
}

$stmt = db()->prepare('DELETE FROM wishlists WHERE user_id = :user_id AND game_id = :game_id');
$stmt->execute(['user_id' => $_SESSION['user_id'], 'game_id' => $gameId]);

sendJson(['success' => true, 'data' => ['game_id' => $gameId], 'message' => 'Game removed from wishlist.'], 200);
