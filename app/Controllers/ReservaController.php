<?php

/**
 * Controller Reserva
 *
 * Responsável por criar nova reserva (action criar):
 * - GET: redireciona para Minhas Reservas (evitar acesso direto ao link).
 * - POST: exige login, valida dados (sala, data, horários), verifica conflito de horário,
 *   grava no ReservaModel e redireciona para Minhas Reservas com mensagem de sucesso ou erro.
 *
 * Rotas: ?c=reserva&a=criar (formulário do modal "Nova Reserva" envia POST para aqui).
 */

class ReservaController
{
    /**
     * Action criar: processa o formulário "Nova Reserva" (POST) ou redireciona (GET).
     */
    public function criar(): void
    {
        // GET: não faz sentido acessar a URL de criar sem enviar o form; redireciona para minhas reservas
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . urlIndex() . '?c=minhas-reservas');
            exit;
        }

        // Proteção de rota: só usuário logado pode criar reserva
        exigirLogin();

        // Dados vindos do formulário (modal Nova Reserva)
        $sala_id = isset($_POST['sala']) ? (int) $_POST['sala'] : 0;
        $data = trim($_POST['data'] ?? '');
        $hora_inicio = trim($_POST['hora_inicio'] ?? '');
        $hora_fim = trim($_POST['hora_fim'] ?? '');
        $observacoes = trim($_POST['observacoes'] ?? '');

        // Validação básica: campos obrigatórios
        if ($sala_id <= 0 || $data === '' || $hora_inicio === '' || $hora_fim === '') {
            $_SESSION['erro_reserva'] = 'Preencha sala, data e horários.';
            header('Location: ' . urlIndex() . '?c=minhas-reservas');
            exit;
        }

        // Validação: data não pode ser no passado (opcional, melhora UX)
        $hoje = date('Y-m-d');
        if ($data < $hoje) {
            $_SESSION['erro_reserva'] = 'A data da reserva não pode ser no passado.';
            header('Location: ' . urlIndex() . '?c=minhas-reservas');
            exit;
        }

        // Validação: horário fim deve ser depois do horário início
        if ($hora_fim <= $hora_inicio) {
            $_SESSION['erro_reserva'] = 'O horário de término deve ser após o horário de início.';
            header('Location: ' . urlIndex() . '?c=minhas-reservas');
            exit;
        }

        $reservaModel = new ReservaModel();

        // Verifica se a sala está disponível no horário (evita conflito com outras reservas)
        if (!$reservaModel->salaDisponivel($sala_id, $data, $hora_inicio, $hora_fim)) {
            $_SESSION['erro_reserva'] = 'Esta sala já está reservada no horário escolhido. Escolha outro horário ou outra sala.';
            header('Location: ' . urlIndex() . '?c=minhas-reservas');
            exit;
        }

        try {
            $usuario_id = (int) $_SESSION['usuario_id'];
            $reservaModel->criar($usuario_id, $sala_id, $data, $hora_inicio, $hora_fim, $observacoes);
            unset($_SESSION['erro_reserva']);
            $_SESSION['sucesso_reserva'] = 'Reserva realizada com sucesso.';
        } catch (PDOException $e) {
            // Ex.: sala_id ou usuario_id inválido (FK)
            $_SESSION['erro_reserva'] = 'Erro ao salvar a reserva. Tente novamente.';
        }

        header('Location: ' . urlIndex() . '?c=minhas-reservas');
        exit;
    }
}
