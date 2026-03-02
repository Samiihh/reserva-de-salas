<?php
/**
 * View Login - Aula 3
 * Formulário POST para index.php?c=auth&a=login. Mensagens de erro/sucesso vêm da sessão.
 */
// Monta as URLs usadas nos links e no action do formulário (funciona na raiz ou em subpasta)
$urlLogin = (BASE_URL === '' ? '' : BASE_URL . '/') . 'index.php?c=auth&a=login';   // página de login (action do form)
$urlRegister = (BASE_URL === '' ? '' : BASE_URL . '/') . 'index.php?c=auth&a=register'; // link "Cadastre-se"
$urlMapa = (BASE_URL === '' ? '' : BASE_URL . '/') . 'index.php?c=mapa';           // redirecionar após login

// Mensagens vindas da sessão (definidas pelo AuthController após POST)
$erro = $_SESSION['erro_login'] ?? null;           // ex.: "Email ou senha incorretos"
$sucesso = $_SESSION['sucesso_cadastro'] ?? null;  // ex.: "Conta criada. Faça login." (após cadastro)

// Limpa da sessão após exibir uma vez, para não reaparecer ao recarregar a página
if (isset($_SESSION['erro_login'])) unset($_SESSION['erro_login']);
if (isset($_SESSION['sucesso_cadastro'])) unset($_SESSION['sucesso_cadastro']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistema de Reserva de Salas</title>
  <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/base.css">
  <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/auth.css">
</head>
<body class="auth-body">
  <div class="auth-card">
    <h1>Sistema de Reserva de Salas</h1>
    <h2>Entrar</h2>
    <?php // Exibe mensagem de erro do login (ex.: "Email ou senha incorretos"); htmlspecialchars evita XSS ?>
    <?php if ($erro): ?>
      <p class="auth-erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>
    <?php // Exibe mensagem de sucesso após cadastro (ex.: "Conta criada. Faça login.") ?>
    <?php if ($sucesso): ?>
      <p class="auth-sucesso"><?= htmlspecialchars($sucesso) ?></p>
    <?php endif; ?>
    <form method="post" action="<?= htmlspecialchars($urlLogin) ?>">
      <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" placeholder="Digite seu email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="senha">Senha</label>
        <input id="senha" type="password" name="senha" placeholder="Digite sua senha" required>
      </div>
      <button type="submit" class="btn-primary">Entrar</button>
    </form>
    <p class="auth-link">
      Não tem conta? <a href="<?= htmlspecialchars($urlRegister) ?>">Cadastre-se</a>
    </p>
    <p class="auth-link">
      <a href="<?= htmlspecialchars($urlMapa) ?>">← Voltar ao mapa</a>
    </p>
  </div>
</body>
</html>
