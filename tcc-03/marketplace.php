<?php
require_once __DIR__ . '/config/database.php';
startSession();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelSwap | Marketplace</title>
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
            </nav>
            <div class="header-actions">
                <a href="/login.php" class="btn btn-secondary">Entrar</a>
            </div>
        </div>
    </header>

    <main class="container page-shell">
        <div class="section-head">
            <div>
                <span class="eyebrow">Catálogo</span>
                <h1>Marketplace</h1>
            </div>
        </div>

        <div class="form-group" style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1rem;">
            <input id="marketplace-search" type="text" placeholder="Buscar por título ou gênero" />
            <select id="genre-filter">
                <option value="">Todos os gêneros</option>
                <option value="Action">Action</option>
                <option value="Adventure">Adventure</option>
                <option value="RPG">RPG</option>
                <option value="Simulation">Simulation</option>
                <option value="Platformer">Platformer</option>
            </select>
            <select id="sort-filter">
                <option value="">Ordenar</option>
                <option value="price_asc">Menor preço</option>
                <option value="price_desc">Maior preço</option>
                <option value="title_asc">Título A-Z</option>
            </select>
        </div>

        <div id="marketplace-grid" class="card-grid" style="margin-top:1.5rem;"></div>
    </main>

    <script src="/frontend/js/app.js"></script>
</body>
</html>
