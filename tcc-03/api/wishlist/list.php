<?php
requireAuth();

$stmt = db()->prepare('SELECT w.*, g.title, g.genre, g.price FROM wishlists w JOIN games g ON g.id = w.game_id WHERE w.user_id = :user_id ORDER BY w.created_at DESC');
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$items = $stmt->fetchAll();

sendJson(['success' => true, 'data' => $items, 'message' => 'Wishlist loaded successfully.'], 200);
