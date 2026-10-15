<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJson(['success' => false, 'data' => null, 'message' => 'Method not allowed.'], 405);
}

startSession();
$data = readJsonBody();
$name = trim((string) ($data['name'] ?? ''));
$email = strtolower(trim((string) ($data['email'] ?? '')));
$password = (string) ($data['password'] ?? '');

if ($name === '' || $email === '' || strlen($password) < 6) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Name, email and password (min 6 chars) are required.'], 400);
}

if (!isValidEmail($email)) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Invalid email format.'], 400);
}

$stmt = db()->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
$stmt->execute(['email' => $email]);
if ($stmt->fetch()) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Email already registered.'], 409);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = db()->prepare('INSERT INTO users (name, email, password_hash, avatar, created_at) VALUES (:name, :email, :password_hash, :avatar, NOW())');
$insert->execute([
    'name' => $name,
    'email' => $email,
    'password_hash' => $hash,
    'avatar' => 'pixel-avatar.png',
]);

$userId = (int) db()->lastInsertId();
$_SESSION['user_id'] = $userId;

sendJson([
    'success' => true,
    'data' => ['user_id' => $userId, 'name' => $name, 'email' => $email],
    'message' => 'User registered successfully.'
], 201);
