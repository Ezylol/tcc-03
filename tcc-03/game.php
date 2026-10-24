<?php
require_once __DIR__ . '/config/database.php';
startSession();
$id = $_GET['id'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelSwap | Jogo</title>
    <link rel="stylesheet" href="frontend/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container nav-wrap">
            <a href="/index.php" class="brand">
                <span class="brand-mark">P</span>
                <span>PixelSwap</span>
            </a>
        </div>
    </header>
    <main class="container page-shell">
        <div id="game-details"></div>
    </main>
    <script src="/frontend/js/app.js"></script>
</body>
</html>
