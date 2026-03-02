<?php

/**
 * Controller Minhas Reservas - Aula 2
 *
 * Responsável por:
 * - Buscar as reservas no Model (ReservaModel).
 * - Preparar os dados para a view (status em badge, data e horário formatados).
 * - Incluir header, view e footer.
 */

class MinhasReservasController
{
    /**
     * Action principal: exibe a página "Minhas Reservas" com dados do banco.
     */
    public function index(): void
    {
        // Busca todas as reservas no banco (com nome da sala via JOIN no Model).
        $reservaModel = new ReservaModel();
        $reservas = $reservaModel->listarTodas();

        // Prepara cada reserva para a view: badge (texto/classe) e data/horário em formato de exibição.
        foreach ($reservas as &$reserva) {
            // Status do badge (pendente, confirmada, cancelada).
            switch ($reserva['status']) {
                case 'confirmada':
                    $reserva['badge_texto'] = 'Confirmada';
                    $reserva['badge_classe'] = 'badge-confirmada';
                    break;
                case 'pendente':
                    $reserva['badge_texto'] = 'Pendente';
                    $reserva['badge_classe'] = 'badge-pendente';
                    break;
                case 'cancelada':
                    $reserva['badge_texto'] = 'Cancelada';
                    $reserva['badge_classe'] = 'badge-cancelada';
                    break;
                default:
                    $reserva['badge_texto'] = $reserva['status'];
                    $reserva['badge_classe'] = '';
            }
            // Data no formato dd/mm/aaaa para exibição (o banco guarda como YYYY-MM-DD).
            $reserva['data_formatada'] = date('d/m/Y', strtotime($reserva['data_reserva']));
            // Horário: "14:00 – 16:00" (hora_inicio e hora_fim; no MySQL vêm como HH:MM:SS).
            $reserva['horario_formatado'] = date('H:i', strtotime($reserva['hora_inicio'])) . ' – ' . date('H:i', strtotime($reserva['hora_fim']));
        }
        unset($reserva); // Boa prática após foreach por referência (&$reserva).

        require BASE_PATH . '/includes/header.php';
        require APP_PATH . '/Views/minhasreservas.php';
        require BASE_PATH . '/includes/footer.php';
    }
}
