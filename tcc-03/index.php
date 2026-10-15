<?php
require_once __DIR__ . '/config/database.php';
startSession();

$currentUser = null;
if (!empty($_SESSION['user_id'])) {
    $stmt = db()->prepare('SELECT id, name, email, avatar FROM users WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $currentUser = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelSwap | Discover. Trade. Play.</title>
    <meta name="description" content="Marketplace indie com chaves digitais, trocas e compras simuladas de jogos." />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                <?php if ($currentUser): ?>
                    <a href="/profile.php">Perfil</a>
                <?php endif; ?>
            </nav>
            <div class="header-actions">
                <div class="search-box">
                    <input id="global-search" type="text" placeholder="Buscar jogos..." />
                    <button type="button" id="search-button">Buscar</button>
                </div>
                <?php if ($currentUser): ?>
                    <a href="/profile.php" class="btn btn-secondary"><?php echo htmlspecialchars($currentUser['name']); ?></a>
                <?php else: ?>
                    <a href="/login.php" class="btn btn-secondary">Entrar</a>
                    <a href="/register.php" class="btn btn-primary">Cadastrar</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container hero-content">
                <div class="hero-copy">
                    <span class="eyebrow">Marketplace indie</span>
                    <h1>Discover. Trade. Play.</h1>
                    <p>Encontre jogos incríveis, venda suas keys e negocie trocas com a comunidade indie.</p>
                    <div class="hero-actions">
                        <a href="/marketplace.php" class="btn btn-primary">Explorar catálogo</a>
                        <a href="/seller.php" class="btn btn-secondary">Vender uma key</a>
                    </div>
                    <ul class="hero-meta">
                        <li>+12.000 gamers</li>
                        <li>+400 anúncios ativos</li>
                        <li>Trocas seguras</li>
                    </ul>
                </div>
                <div class="hero-panel">
                    <div class="mini-card">
                        <span>Oferta do dia</span>
                        <h3>Hollow Knight</h3>
                        <p>Chave digital com desconto especial</p>
                        <strong>R$ 18,90</strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="container section">
            <div class="section-head">
                <div>
                    <span class="eyebrow">Destaques</span>
                    <h2>Jogos em destaque</h2>
                </div>
            </div>
            <div id="featured-games" class="card-grid"></div>
        </section>

        <section class="container section alt-bg">
            <div class="section-head">
                <div>
                    <span class="eyebrow">Novidades</span>
                    <h2>Ofertas recentes</h2>
                </div>
            </div>
            <div id="recent-offers" class="card-grid compact"></div>
        </section>

        <section class="container section">
            <div class="section-head">
                <div>
                    <span class="eyebrow">Popular</span>
                    <h2>Mais populares</h2>
                </div>
            </div>
            <div id="popular-games" class="card-grid"></div>
        </section>

        <section class="container section alt-bg">
            <div class="section-head">
                <div>
                    <span class="eyebrow">Troca</span>
                    <h2>Keys disponíveis para troca</h2>
                </div>
            </div>
            <div id="trade-listings" class="card-grid compact"></div>
        </section>

        <section class="container section explain" id="how-it-works">
            <div class="section-head center">
                <span class="eyebrow">Como funciona</span>
                <h2>Uma loja indie construída para comunidade</h2>
            </div>
            <div class="info-grid">
                <article>
                    <span class="badge">01</span>
                    <h3>Explora</h3>
                    <p>Descubra títulos indie cults, lançamentos e raridades curadas pela comunidade.</p>
                </article>
                <article>
                    <span class="badge">02</span>
                    <h3>Compra</h3>
                    <p>Compre chaves digitais de forma simples em um marketplace seguro e transparente.</p>
                </article>
                <article>
                    <span class="badge">03</span>
                    <h3>Troca</h3>
                    <p>Negocie sua biblioteca e transforme chaves em novas experiências sem burocracia.</p>
                </article>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <a href="/index.php" class="brand footer-brand">
                    <span class="brand-mark">P</span>
                    <span>PixelSwap</span>
                </a>
                <p>Marketplace de jogos independentes para descobrir, trocar e manter a biblioteca em constante evolução.</p>
            </div>
            <div>
                <h4>Links</h4>
                <ul>
                    <li><a href="/marketplace.php">Marketplace</a></li>
                    <li><a href="/seller.php">Vendedor</a></li>
                    <li><a href="#how-it-works">Como funciona</a></li>
                </ul>
            </div>
            <div>
                <h4>Suporte</h4>
                <ul>
                    <li><a href="/login.php">Login</a></li>
                    <li><a href="/register.php">Cadastro</a></li>
                    <li><a href="/profile.php">Perfil</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script src="/frontend/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            renderHomeSections();
            bindSearch();
        });
    </script>
</body>
</html>
