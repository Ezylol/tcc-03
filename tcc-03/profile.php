<?php
require_once __DIR__ . '/config/database.php';
startSession();
if (empty($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelSwap | Perfil</title>
    <link rel="stylesheet" href="frontend/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container nav-wrap">
            <a href="/index.php" class="brand">
                <span class="brand-mark">P</span>
                <span>PixelSwap</span>
            </a>
            <nav class="site-nav">
                <a href="/index.php">Home</a>
                <a href="/marketplace.php">Marketplace</a>
                <a href="/seller.php">Vendedor</a>
                <a href="/profile.php">Perfil</a>
            </nav>
            <div class="header-actions">
                <button class="btn btn-secondary" type="button" onclick="logoutUser()">Sair</button>
            </div>
        </div>
    </header>

    <main class="container page-shell">
        <section class="profile-hero">
            <div class="profile-avatar" id="profile-avatar">U</div>
            <div>
                <span class="eyebrow">Perfil</span>
                <h1 id="profile-name">Carregando...</h1>
                <p id="profile-email">carregando@email.com</p>
            </div>
        </section>

        <div class="stats-grid">
            <div class="stat-card">
                <span>Jogos comprados</span>
                <strong id="stats-orders">0</strong>
            </div>
            <div class="stat-card">
                <span>Trocas ativas</span>
                <strong id="stats-trades">0</strong>
            </div>
            <div class="stat-card">
                <span>Lista de desejos</span>
                <strong id="stats-wishlist">0</strong>
            </div>
        </div>

        <section class="section">
            <h2>Biblioteca do usuário</h2>
            <div class="card-grid" id="library-grid"></div>
        </section>

        <section class="section">
            <h2>Lista de desejos</h2>
            <div class="card-grid" id="wishlist-grid"></div>
        </section>

        <section class="section">
            <h2>Propostas de troca</h2>
            <div class="card-grid" id="trades-grid"></div>
        </section>
    </main>

    <script src="/frontend/js/app.js"></script>
</body>
</html>
