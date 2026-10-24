<?php
require_once __DIR__ . '/config/database.php';
startSession();
if (!empty($_SESSION['user_id'])) {
    header('Location: /profile.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelSwap | Login</title>
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
        <section class="form-shell">
            <h1>Entrar na plataforma</h1>
            <div id="login-message" class="message"></div>
            <form id="login-form">
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input id="login-email" name="email" type="email" required placeholder="seu@email.com">
                </div>
                <div class="form-group">
                    <label for="login-password">Senha</label>
                    <input id="login-password" name="password" type="password" required placeholder="Sua senha">
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Entrar</button>
                    <a href="/register.php" class="btn btn-secondary">Criar conta</a>
                </div>
            </form>
        </section>
    </main>
    <script src="/frontend/js/app.js"></script>
</body>
</html>
