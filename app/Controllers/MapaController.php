<?php

/**
 * Controller do Mapa de Salas - Aula 1
 * Responsável por exibir a página do mapa.
 */

// Classe que controla a exibição da tela do mapa de salas
class MapaController
{
    // Método principal chamado quando o usuário acessa a rota do mapa (ex: /mapa)
    public function index(): void
    {
        // Carrega o cabeçalho comum do site (menu, logo, etc.)
        require BASE_PATH . '/includes/header.php';

        // Carrega o conteúdo específico da página do mapa
        require APP_PATH . '/Views/mapa.php';

        // Carrega o rodapé comum do site
        require BASE_PATH . '/includes/footer.php';
    }
}
