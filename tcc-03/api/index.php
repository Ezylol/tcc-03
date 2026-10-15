<?php

require_once __DIR__ . '/../includes/functions.php';

startSession();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$route = $_GET['route'] ?? '/';
$route = trim($route, '/');
$segments = $route === '' ? [] : explode('/', $route);

if (empty($segments)) {
    sendJson([
        'success' => true,
        'data' => ['project' => 'PixelSwap API', 'version' => '1.0.0'],
        'message' => 'API ready.'
    ], 200);
}

$resource = $segments[0] ?? '';
$action = $segments[1] ?? null;
$id = $segments[2] ?? null;

try {
    switch ($resource) {
        case 'auth':
            if ($method === 'POST' && ($action === 'register' || $action === 'login' || $action === 'logout')) {
                require __DIR__ . '/auth/' . $action . '.php';
                exit;
            }
            if ($method === 'GET' && $action === 'me') {
                require __DIR__ . '/auth/me.php';
                exit;
            }
            break;

        case 'games':
            if ($method === 'GET') {
                if ($action !== null && is_numeric($action)) {
                    $_GET['id'] = $action;
                    require __DIR__ . '/games/show.php';
                    exit;
                }
                require __DIR__ . '/games/list.php';
                exit;
            }
            break;

        case 'listings':
            if ($method === 'GET') {
                require __DIR__ . '/listings/list.php';
                exit;
            }
            if ($method === 'POST') {
                require __DIR__ . '/listings/create.php';
                exit;
            }
            if ($method === 'PUT' && $action !== null && is_numeric($action)) {
                $_GET['id'] = $action;
                require __DIR__ . '/listings/update.php';
                exit;
            }
            if ($method === 'DELETE' && $action !== null && is_numeric($action)) {
                $_GET['id'] = $action;
                require __DIR__ . '/listings/delete.php';
                exit;
            }
            break;

        case 'orders':
            if ($method === 'GET') {
                require __DIR__ . '/orders/list.php';
                exit;
            }
            if ($method === 'POST') {
                require __DIR__ . '/orders/create.php';
                exit;
            }
            break;

        case 'trades':
            if ($method === 'GET') {
                require __DIR__ . '/trades/list.php';
                exit;
            }
            if ($method === 'POST') {
                require __DIR__ . '/trades/create.php';
                exit;
            }
            if ($method === 'PATCH' && $action !== null && is_numeric($action)) {
                $_GET['id'] = $action;
                $_POST['_method'] = 'PATCH';
                $decision = $id ?? null;
                if ($decision === 'accept') {
                    require __DIR__ . '/trades/accept.php';
                    exit;
                }
                if ($decision === 'reject') {
                    require __DIR__ . '/trades/reject.php';
                    exit;
                }
            }
            break;

        case 'wishlist':
            if ($method === 'GET') {
                require __DIR__ . '/wishlist/list.php';
                exit;
            }
            if ($method === 'POST') {
                require __DIR__ . '/wishlist/create.php';
                exit;
            }
            if ($method === 'DELETE') {
                require __DIR__ . '/wishlist/delete.php';
                exit;
            }
            break;
    }

    sendJson([
        'success' => false,
        'data' => null,
        'message' => 'Route not found.',
    ], 404);
} catch (Throwable $exception) {
    sendJson([
        'success' => false,
        'data' => null,
        'message' => 'Unexpected server error.',
        'error' => $exception->getMessage(),
    ], 500);
}
