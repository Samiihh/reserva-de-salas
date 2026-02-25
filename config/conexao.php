<?php
/**
 * config/conexao.php
 * Conexão simples com PDO
 */

$host = 'localhost';
$db   = 'reserva_salas';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // lança erro como Exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // retorna array associativo
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Erro na conexão com o banco: ' . $e->getMessage());
}
