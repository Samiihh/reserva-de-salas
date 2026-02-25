<?php
require_once __DIR__ . '/../../config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Reserva de Salas</title>

    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/base.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/auth.css">
</head>

<body class="auth-body">

    <div class="auth-card">
        <h1>Sistema de Reserva de Salas</h1>
        <h2>Entrar</h2>

        <form id="form-login">
            <div class="form-group">
                <label>Email</label>
                <input type="email" placeholder="Digite seu email" required>
            </div>

            <div class="form-group">
                <label>Senha</label>
                <input type="password" placeholder="Digite sua senha" required>
            </div>

            <button type="submit" class="btn-primary">
                Entrar
            </button>
        </form>

        <p class="auth-link">
            Não tem conta?
            <a href="<?= BASE_URL ?>/app/auth/register.php">Cadastre-se</a>
        </p>

        <p class="auth-link">
            <a href="<?= BASE_URL ?>/index.php?c=mapa">← Voltar ao mapa</a>
        </p>
    </div>

    <script>
      (function () {
        sessionStorage.removeItem('logado');
        document.getElementById('form-login').addEventListener('submit', function (e) {
          e.preventDefault();
          sessionStorage.setItem('logado', '1');
          window.location.href = '<?= BASE_URL ?>/index.php';
        });
      })();
    </script>
</body>

</html>