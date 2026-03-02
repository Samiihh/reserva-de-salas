<?php

/**
 * Controller do Mapa de Salas - Aula 1 + Aula 2
 *
 * Responsável por:
 * - Buscar as salas no Model (SalaModel).
 * - Preparar os dados para a view (texto e classe do badge).
 * - Incluir header, view do mapa e footer.
 *
 * Toda a lógica fica aqui; a view só exibe o que o controller envia.
 */

class MapaController
{
    /**
     * Action principal: exibe a página do mapa de salas.
     * Busca dados no banco, prepara para exibição e chama a view.
     */
    public function index(): void
    {
        // --- Buscar dados no banco via Model (POO) ---
        $salaModel = new SalaModel();
        $salas = $salaModel->listarTodas();

        // --- Preparar dados para a view (lógica do badge no controller) ---
        // O status no banco vem como: 'disponivel', 'em_uso', 'manutencao'.
        // Adicionamos em cada sala: badge_texto (para exibir) e badge_classe (CSS do badge).
        // Uso de &$sala: alteramos o próprio item do array dentro do loop.
        foreach ($salas as &$sala) {
            switch ($sala['status']) {
                case 'disponivel':
                    $sala['badge_texto'] = 'Disponível';
                    $sala['badge_classe'] = 'badge-disponivel';
                    break;
                case 'em_uso':
                    $sala['badge_texto'] = 'Em uso';
                    $sala['badge_classe'] = 'badge-em-uso';
                    break;
                case 'manutencao':
                    $sala['badge_texto'] = 'Manutenção';
                    $sala['badge_classe'] = 'badge-manutencao';
                    break;
                default:
                    $sala['badge_texto'] = $sala['status'];
                    $sala['badge_classe'] = '';
            }
        }
        unset($sala); // Boa prática: após foreach por referência (&$sala), remove a referência.

        // --- Incluir layout: header, conteúdo (view), footer ---
        require BASE_PATH . '/includes/header.php';
        require APP_PATH . '/Views/mapa.php';
        require BASE_PATH . '/includes/footer.php';
    }
}
