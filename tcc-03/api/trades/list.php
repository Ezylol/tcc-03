<?php
requireAuth();

$sql = 'SELECT t.*, g.title as requested_title, u_sender.name as sender_name, u_receiver.name as receiver_name FROM trades t JOIN listings l ON l.id = t.requested_listing_id JOIN games g ON g.id = l.game_id JOIN users u_sender ON u_sender.id = t.sender_user_id JOIN users u_receiver ON u_receiver.id = t.receiver_user_id WHERE t.sender_user_id = :user_id OR t.receiver_user_id = :user_id ORDER BY t.created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$trades = $stmt->fetchAll();

sendJson(['success' => true, 'data' => $trades, 'message' => 'Trades loaded successfully.'], 200);
