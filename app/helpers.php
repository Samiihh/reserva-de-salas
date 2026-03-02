<?php

/**
 * Funções auxiliares usadas pelos Controllers
 * Carregado pelo index.php antes de chamar qualquer controller.
 */

/**
 * Monta a URL base do index.php (para redirecionamentos).
 * Funciona na raiz (localhost) ou em subpasta (ex.: /reserva-de-salas).
 *
 * @return string Ex.: /reserva-de-salas/index.php ou index.php
 */
function urlIndex(): string
{
    return (BASE_URL === '' ? '' : BASE_URL . '/') . 'index.php';
}

/**
 * Redireciona o usuário para a tela de login se não estiver logado.
 * Usado em rotas protegidas (Minhas Reservas, Nova Reserva).
 * Encerra a execução com exit após o redirect.
 */
function exigirLogin(): void
{
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . urlIndex() . '?c=auth&a=login');
        exit;
    }
}
