<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJson(['success' => false, 'data' => null, 'message' => 'Method not allowed.'], 405);
}

startSession();
$data = readJsonBody();
$email = strtolower(trim((string) ($data['email'] ?? '')));
$password = (string) ($data['password'] ?? '');

if ($email === '' || $password === '') {
    sendJson(['success' => false, 'data' => null, 'message' => 'Email and password are required.'], 400);
}

$stmt = db()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    sendJson(['success' => false, 'data' => null, 'message' => 'Invalid credentials.'], 401);
}

$_SESSION['user_id'] = (int) $user['id'];

sendJson([
    'success' => true,
    'data' => ['user' => ['id' => (int) $user['id'], 'name' => $user['name'], 'email' => $user['email']]],
    'message' => 'Login successful.'
], 200);
