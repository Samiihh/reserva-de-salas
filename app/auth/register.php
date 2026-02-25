<?php
require_once __DIR__ . '/../../config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Sistema de Reserva de Salas</title>

    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/base.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/auth.css">
</head>

<body class="auth-body">

    <div class="auth-card">
        <h1>Sistema de Reserva de Salas</h1>
        <h2>Criar conta</h2>

        <form>
            <div class="form-group">
                <label>Nome</label>
                <input type="text" placeholder="Digite seu nome completo" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" placeholder="Digite seu email" required>
            </div>

            <div class="form-group">
                <label>Senha</label>
                <input type="password" placeholder="Crie uma senha" required>
            </div>

            <div class="form-group">
                <label>Confirmar senha</label>
                <input type="password" placeholder="Repita sua senha" required>
            </div>

            <button type="submit" class="btn-primary">
                Cadastrar
            </button>
        </form>

        <p class="auth-link">
            Já tem conta?
            <a href="<?= BASE_URL ?>/app/auth/login.php">Entrar</a>
        </p>
    </div>

</body>
</html>