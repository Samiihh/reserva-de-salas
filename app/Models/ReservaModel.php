<?php

/**
 * Model Reserva - Aula 2 (POO)
 *
 * Responsável pela comunicação com a tabela "reservas" no banco:
 * - listarTodas(): lista todas as reservas (com nome da sala).
 * - listarPorUsuario($usuario_id): lista apenas as reservas do usuário (para "Minhas Reservas").
 * - salaDisponivel(): verifica se a sala está livre no horário (evita conflito).
 * - criar(): insere uma nova reserva (usuário logado, sala, data, horários, observações).
 *
 * Usa POO: instância (new ReservaModel()), conexão guardada no objeto ($this->pdo).
 */

require_once CONFIG_PATH . '/conexao.php';

class ReservaModel
{
    /**
     * Conexão PDO usada para executar as queries.
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Construtor: ao fazer new ReservaModel(), pega o $pdo global e guarda em $this->pdo.
     */
    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     * Lista todas as reservas cadastradas, com o nome da sala (JOIN na tabela salas).
     * Útil para admin; a tela "Minhas Reservas" usa listarPorUsuario().
     *
     * @return array Cada item: id, sala_id, sala_nome, data_reserva, hora_inicio, hora_fim, status, observacoes.
     */
    public function listarTodas(): array
    {
        // INNER JOIN com salas: traz o nome da sala (s.nome AS sala_nome) em cada linha. Ordena por data/hora mais recentes (DESC).
        $sql = "SELECT r.id, r.sala_id, r.data_reserva, r.hora_inicio, r.hora_fim, r.status, r.observacoes,
                       s.nome AS sala_nome
                FROM reservas r
                INNER JOIN salas s ON r.sala_id = s.id
                ORDER BY r.data_reserva DESC, r.hora_inicio DESC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lista apenas as reservas do usuário informado (para a página "Minhas Reservas").
     * Considera todas as reservas do usuário (pendente, confirmada, cancelada); a view exibe o status.
     *
     * @param int $usuario_id ID do usuário logado (vindo de $_SESSION['usuario_id'])
     * @return array Mesmo formato de listarTodas(), mas filtrado por usuario_id
     */
    public function listarPorUsuario(int $usuario_id): array
    {
        $sql = "SELECT r.id, r.sala_id, r.data_reserva, r.hora_inicio, r.hora_fim, r.status, r.observacoes,
                       s.nome AS sala_nome
                FROM reservas r
                INNER JOIN salas s ON r.sala_id = s.id
                WHERE r.usuario_id = :usuario_id
                ORDER BY r.data_reserva DESC, r.hora_inicio DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['usuario_id' => $usuario_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Verifica se a sala está disponível no dia e horário informados (evita sobreposição).
     * Considera apenas reservas com status 'pendente' ou 'confirmada' (canceladas não bloqueiam).
     * Dois intervalos se sobrepõem se: inicio_novo < fim_existente E fim_novo > inicio_existente.
     *
     * @param int $sala_id ID da sala
     * @param string $data_reserva Data no formato Y-m-d (igual ao banco)
     * @param string $hora_inicio Horário início (HH:MM ou HH:MM:SS)
     * @param string $hora_fim Horário fim (HH:MM ou HH:MM:SS)
     * @param int|null $excluir_reserva_id Se estiver editando, excluir esta reserva da checagem
     * @return bool true = sala livre; false = já existe reserva no horário
     */
    public function salaDisponivel(int $sala_id, string $data_reserva, string $hora_inicio, string $hora_fim, ?int $excluir_reserva_id = null): bool
    {
        $sql = "SELECT COUNT(*) FROM reservas
                WHERE sala_id = :sala_id
                  AND data_reserva = :data_reserva
                  AND status IN ('pendente', 'confirmada')
                  AND hora_inicio < :hora_fim
                  AND hora_fim > :hora_inicio";
        $params = [
            'sala_id' => $sala_id,
            'data_reserva' => $data_reserva,
            'hora_inicio' => $hora_inicio,
            'hora_fim' => $hora_fim,
        ];
        if ($excluir_reserva_id !== null) {
            $sql .= " AND id != :excluir_id";
            $params['excluir_id'] = $excluir_reserva_id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $count = (int) $stmt->fetchColumn();
        return $count === 0;
    }

    /**
     * Cria uma nova reserva no banco (usuário, sala, data, horários, observações).
     * O status inicial é 'pendente' (definido no schema da tabela).
     *
     * @param int $usuario_id ID do usuário logado
     * @param int $sala_id ID da sala
     * @param string $data_reserva Data no formato Y-m-d
     * @param string $hora_inicio Horário início (HH:MM ou HH:MM:SS)
     * @param string $hora_fim Horário fim (HH:MM ou HH:MM:SS)
     * @param string $observacoes Texto opcional
     * @return void
     * @throws PDOException Em caso de erro no banco (ex.: FK inválida)
     */
    public function criar(int $usuario_id, int $sala_id, string $data_reserva, string $hora_inicio, string $hora_fim, string $observacoes = ''): void
    {
        $sql = "INSERT INTO reservas (usuario_id, sala_id, data_reserva, hora_inicio, hora_fim, observacoes)
                VALUES (:usuario_id, :sala_id, :data_reserva, :hora_inicio, :hora_fim, :observacoes)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'sala_id' => $sala_id,
            'data_reserva' => $data_reserva,
            'hora_inicio' => $hora_inicio,
            'hora_fim' => $hora_fim,
            'observacoes' => $observacoes,
        ]);
    }
}
