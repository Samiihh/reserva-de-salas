<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Reserva de Salas</title>

  <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body>

<header class="app-header">
  <div class="app-header__container">
    <a href="mapa.php" class="app-brand">
      <span class="app-brand__logo" aria-hidden="true">🏫</span>
      <span class="app-brand__text">
        <span class="app-brand__title">Sistema de Reserva</span>
        <span class="app-brand__subtitle">de Salas</span>
      </span>
    </a>

    <nav class="app-nav" aria-label="Menu principal">
      <span class="nav-guest">
        <a class="app-nav__link" href="login.php">Entrar</a>
      </span>
      <span class="nav-logado">
        <a class="app-nav__link" href="mapa.php">Mapa de Salas</a>
        <a class="app-nav__link" href="minhasreservas.php">Minhas Reservas</a>
        <a class="app-nav__link app-nav__link--primary abrir-modal-nova-reserva" href="#" aria-haspopup="dialog" aria-expanded="false">Nova Reserva</a>
      </span>
    </nav>

    <div class="app-user nav-logado">
      <span class="app-user__greeting">Olá, <strong>Samy</strong></span>
      <a class="app-user__logout" href="login.php">Sair</a>
    </div>
  </div>
</header>

<?php include 'includes/modal-nova-reserva.php'; ?>