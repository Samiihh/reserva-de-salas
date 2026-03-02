<?php

/**
 * Model Usuario - Aula 3
 *
 * Responsável pela tabela "usuarios": buscar por email (login) e criar usuário (cadastro).
 * Senha: ao criar usar password_hash(); ao logar usar password_verify() no controller.
 */

require_once CONFIG_PATH . '/conexao.php';

class UsuarioModel
{
    private PDO $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     * Busca um usuário pelo email (para login).
     * Usa prepared statement (? ) para evitar SQL injection.
     *
     * @return array|null Uma linha (id, nome, email, senha) ou null se não existir.
     */
    public function buscarPorEmail(string $email): ?array
    {
        $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Cria um novo usuário. A senha deve vir já hasheada (password_hash no controller).
     *
     * @return true em sucesso
     */
    public function criar(string $nome, string $email, string $senhaHash): bool
    {
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nome, $email, $senhaHash]);
        return true;
    }
}
