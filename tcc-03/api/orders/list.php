<?php
requireAuth();

$stmt = db()->prepare('SELECT o.*, g.title, u.name as seller_name FROM orders o JOIN listings l ON l.id = o.listing_id JOIN games g ON g.id = l.game_id JOIN users u ON u.id = l.seller_id WHERE o.buyer_id = :buyer_id ORDER BY o.created_at DESC');
$stmt->execute(['buyer_id' => $_SESSION['user_id']]);
$orders = $stmt->fetchAll();

sendJson(['success' => true, 'data' => $orders, 'message' => 'Orders loaded successfully.'], 200);
