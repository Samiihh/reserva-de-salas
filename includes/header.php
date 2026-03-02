<?php
/**
 * Header - Aula 3
 * Rotas do MVC: sempre index.php?c=...&a=... (nunca app/auth/login.php).
 * Quando logado: body ganha class="user-logado" para o CSS mostrar o menu (nav-logado).
 */
$base = (BASE_URL === '' ? '' : rtrim(BASE_URL, '/') . '/');
$urlIndex = $base . 'index.php';
$urlLogin  = $base . 'index.php?c=auth&a=login';
$urlLogout = $base . 'index.php?c=auth&a=logout';
$logado = isset($_SESSION['usuario_id']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Reserva de Salas</title>
  <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/main.css">
</head>
<!-- user-logado: o CSS (app.css) usa body.user-logado para exibir .nav-logado e esconder .nav-guest -->
<body<?= $logado ? ' class="user-logado"' : '' ?>>

<header class="app-header">
  <div class="app-header__container">
    <a href="<?= $urlIndex ?>" class="app-brand">
      <span class="app-brand__logo" aria-hidden="true">🏫</span>
      <span class="app-brand__text">
        <span class="app-brand__title">Sistema de Reserva</span>
        <span class="app-brand__subtitle">de Salas</span>
      </span>
    </a>

    <nav class="app-nav" aria-label="Menu principal">
      <?php if (!$logado): ?>
        <span class="nav-guest">
          <a class="app-nav__link" href="<?= $urlLogin ?>">Entrar</a>
        </span>
      <?php else: ?>
        <span class="nav-logado">
          <a class="app-nav__link" href="<?= $urlIndex ?>">Mapa de Salas</a>
          <a class="app-nav__link" href="<?= $urlIndex ?>?c=minhas-reservas">Minhas Reservas</a>
          <button type="button" class="app-nav__link app-nav__link--primary abrir-modal-nova-reserva" aria-haspopup="dialog" aria-expanded="false">Nova Reserva</button>
        </span>
      <?php endif; ?>
    </nav>

    <?php if ($logado): ?>
    <div class="app-user nav-logado">
      <span class="app-user__greeting">Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong></span>
      <a class="app-user__logout" href="<?= $urlLogout ?>">Sair</a>
    </div>
    <?php endif; ?>
  </div>
</header>

<?php include __DIR__ . '/modal-nova-reserva.php'; ?>
