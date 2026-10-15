<?php

require_once __DIR__ . '/../config/database.php';

function sendJson(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

function safeString(?string $value): string
{
    return trim((string) $value);
}

function routeParam(string $value): ?string
{
    return $value === '' ? null : $value;
}

function generatePasswordHash(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function getCurrentUserId(): ?int
{
    startSession();
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function currentUser(): ?array
{
    $userId = getCurrentUserId();
    if (!$userId) {
        return null;
    }

    $stmt = db()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $userId]);
    return $stmt->fetch();
}
