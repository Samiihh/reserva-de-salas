<?php

/**
 * Controller de Minhas Reservas
 * Responsável por exibir a página de reservas do usuário.
 */

// Classe que controla a exibição da tela "Minhas Reservas"
class MinhasReservasController
{
    // Método principal chamado quando o usuário acessa a rota de minhas reservas (ex: /minhas-reservas)
    public function index(): void
    {
        // Carrega o cabeçalho comum do site (menu, logo, etc.)
        require BASE_PATH . '/includes/header.php';

        // Carrega o conteúdo da página com a lista de reservas do usuário
        require APP_PATH . '/Views/minhasreservas.php';

        // Carrega o rodapé comum do site
        require BASE_PATH . '/includes/footer.php';
    }
}
