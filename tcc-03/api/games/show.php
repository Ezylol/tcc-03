<?php

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Game id is required.'], 400);
}

$stmt = db()->prepare('SELECT * FROM games WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$game = $stmt->fetch();

if (!$game) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Game not found.'], 404);
}

sendJson(['success' => true, 'data' => $game, 'message' => 'Game loaded successfully.'], 200);
