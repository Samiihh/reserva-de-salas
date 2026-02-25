<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Reserva de Salas</title>

  <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/main.css">
</head>

<body>

<header class="app-header">
  <div class="app-header__container">
    <a href="index.php" class="app-brand">
      <span class="app-brand__logo" aria-hidden="true">🏫</span>
      <span class="app-brand__text">
        <span class="app-brand__title">Sistema de Reserva</span>
        <span class="app-brand__subtitle">de Salas</span>
      </span>
    </a>

    <nav class="app-nav" aria-label="Menu principal">
      <span class="nav-guest">
        <a class="app-nav__link" href="<?= BASE_URL ?>/app/auth/login.php">Entrar</a>
      </span>
      <span class="nav-logado">
        <a class="app-nav__link" href="index.php">Mapa de Salas</a>
        <a class="app-nav__link" href="index.php?c=minhas-reservas">Minhas Reservas</a>
        <button type="button" class="app-nav__link app-nav__link--primary abrir-modal-nova-reserva" aria-haspopup="dialog" aria-expanded="false">Nova Reserva</button>
      </span>
    </nav>

    <div class="app-user nav-logado">
      <span class="app-user__greeting">Olá, <strong>Samy</strong></span>
      <a class="app-user__logout" href="<?= BASE_URL ?>/app/auth/login.php">Sair</a>
    </div>
  </div>
</header>

<?php include 'includes/modal-nova-reserva.php'; ?>