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
    <title>PixelSwap | Cadastro</title>
    <link rel="stylesheet" href="/frontend/css/style.css">
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
            <h1>Criar uma conta</h1>
            <div id="register-message" class="message"></div>
            <form id="register-form">
                <div class="form-group">
                    <label for="register-name">Nome</label>
                    <input id="register-name" name="name" type="text" required placeholder="Seu nome">
                </div>
                <div class="form-group">
                    <label for="register-email">Email</label>
                    <input id="register-email" name="email" type="email" required placeholder="seu@email.com">
                </div>
                <div class="form-group">
                    <label for="register-password">Senha</label>
                    <input id="register-password" name="password" type="password" required placeholder="Crie uma senha forte">
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Cadastrar</button>
                    <a href="/login.php" class="btn btn-secondary">Já tenho conta</a>
                </div>
            </form>
        </section>
    </main>
    <script src="/frontend/js/app.js"></script>
</body>
</html>
