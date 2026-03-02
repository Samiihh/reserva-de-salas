<?php
/**
 * View Cadastro - Aula 3
 * Formulário POST para index.php?c=auth&a=register. Erros vêm de $_SESSION['erro_cadastro'].
 */
$urlRegister = (BASE_URL === '' ? '' : BASE_URL . '/') . 'index.php?c=auth&a=register';
$urlLogin = (BASE_URL === '' ? '' : BASE_URL . '/') . 'index.php?c=auth&a=login';
$erro = $_SESSION['erro_cadastro'] ?? null;
if (isset($_SESSION['erro_cadastro'])) unset($_SESSION['erro_cadastro']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - Sistema de Reserva de Salas</title>
  <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/base.css">
  <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/auth.css">
</head>
<body class="auth-body">
  <div class="auth-card">
    <h1>Sistema de Reserva de Salas</h1>
    <h2>Criar conta</h2>
    <?php if ($erro): ?>
      <p class="auth-erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>
    <form method="post" action="<?= htmlspecialchars($urlRegister) ?>">
      <div class="form-group">
        <label for="nome">Nome</label>
        <input id="nome" type="text" name="nome" placeholder="Digite seu nome completo" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" placeholder="Digite seu email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="senha">Senha</label>
        <input id="senha" type="password" name="senha" placeholder="Crie uma senha (mín. 6 caracteres)" required minlength="6">
      </div>
      <div class="form-group">
        <label for="confirmar_senha">Confirmar senha</label>
        <input id="confirmar_senha" type="password" name="confirmar_senha" placeholder="Repita sua senha" required minlength="6">
      </div>
      <button type="submit" class="btn-primary">Cadastrar</button>
    </form>
    <p class="auth-link">
      Já tem conta? <a href="<?= htmlspecialchars($urlLogin) ?>">Entrar</a>
    </p>
  </div>
</body>
</html>
