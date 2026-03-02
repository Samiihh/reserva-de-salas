<?php

/**
 * Model Sala - Aula 2 (POO)
 *
 * Responsável por toda a comunicação com a tabela "salas" no banco de dados.
 * Só busca dados (lista); não cria nem altera. Retorna array de arrays para a view.
 *
 * Usa POO: instância (new SalaModel()), conexão guardada no objeto ($this->pdo).
 */

// Garante que a conexão PDO exista; o construtor guarda no objeto.
require_once CONFIG_PATH . '/conexao.php';

class SalaModel
{
    /**
     * Conexão PDO usada para executar as queries.
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Construtor: ao fazer new SalaModel(), pega o $pdo global e guarda em $this->pdo.
     * @throws \RuntimeException Se a conexão com o banco não estiver disponível.
     */
    public function __construct()
    {
        global $pdo;
        if ($pdo === null || !$pdo instanceof PDO) {
            throw new \RuntimeException(
                'Conexão com o banco não disponível. Verifique config/conexao.php (host, banco, usuário e senha) e se o MySQL está rodando.'
            );
        }
        $this->pdo = $pdo;
    }

    /**
     * Lista todas as salas cadastradas no banco.
     *
     * @return array Lista de salas; cada item é um array com id, nome, capacidade, andar, recursos, status.
     */
    public function listarTodas(): array
    {
        // Monta a consulta SQL: colunas usadas na tela do mapa, ordenadas por nome.
        $sql = "SELECT id, nome, capacidade, andar, recursos, status
                FROM salas
                ORDER BY nome ASC";

        // Executa a query usando a conexão guardada no objeto ($this->pdo).
        $stmt = $this->pdo->query($sql);

        // fetchAll(PDO::FETCH_ASSOC): retorna todas as linhas como array de arrays associativos.
        // Ex.: [ ['id' => 1, 'nome' => 'Sala 101', ...], ['id' => 2, 'nome' => 'Sala 102', ...], ... ]
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
