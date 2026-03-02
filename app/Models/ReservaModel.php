<?php

/**
 * Model Reserva - Aula 2 (POO)
 *
 * Responsável pela comunicação com a tabela "reservas" no banco.
 * Só busca dados (lista); não cria nem altera. Retorna array de arrays para a view.
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
}
