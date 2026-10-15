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
    <title>PixelSwap | Vendedor</title>
    <link rel="stylesheet" href="/frontend/css/style.css">
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
        </div>
    </header>
    <main class="container page-shell">
        <div class="dashboard-grid">
            <section class="form-shell">
                <h1>Novo anúncio</h1>
                <div id="listing-message" class="message"></div>
                <form id="listing-form">
                    <div class="form-group">
                        <label for="game-id">Jogo</label>
                        <select id="game-id" name="game_id">
                            <option value="1">Hollow Knight</option>
                            <option value="2">Celeste</option>
                            <option value="3">Stardew Valley</option>
                            <option value="4">Terraria</option>
                            <option value="5">Dead Cells</option>
                            <option value="6">Undertale</option>
                            <option value="7">Rain World</option>
                            <option value="8">OMORI</option>
                            <option value="9">The Binding of Isaac: Rebirth</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Preço</label>
                        <input id="price" name="price" type="number" step="0.01" min="0" value="19.99">
                    </div>
                    <div class="form-group">
                        <label for="listing-type">Tipo</label>
                        <select id="listing-type" name="listing_type">
                            <option value="sale">Venda</option>
                            <option value="trade">Troca</option>
                        </select>
                    </div>
                    <div class="form-group checkbox-row">
                        <label>
                            <input type="checkbox" name="accepts_trade" value="1"> Aceita trocas
                        </label>
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">Publicar anúncio</button>
                    </div>
                </form>
            </section>

            <section class="panel-shell">
                <h2>Seus anúncios</h2>
                <div id="seller-listings" class="list-stack"></div>
            </section>
        </div>
    </main>
    <script src="/frontend/js/app.js"></script>
</body>
</html>
