<?php

/**
 * Configuração de caminhos do projeto
 * Aula 1
 */

// --- Caminhos no disco (para require, file_exists, etc.) ---

// Raiz do projeto: pasta onde está o index.php (config.php fica em /config, __DIR__ sobe um nível)
define('BASE_PATH', dirname(__DIR__));

// Pasta da aplicação: Controllers, Models, Views, autoload
define('APP_PATH', BASE_PATH . '/app');

// Pasta de configuração (este arquivo, conexao.php, etc.)
define('CONFIG_PATH', BASE_PATH . '/config');

// Pasta de arquivos estáticos: CSS, JS, imagens
define('ASSETS_PATH', BASE_PATH . '/assets');

// Pasta de includes reutilizáveis: header.php, footer.php, modais
define('INCLUDES_PATH', BASE_PATH . '/includes');

// --- URLs para o navegador (links, <link href>, <script src>) ---

// Nome do script em execução (ex: /reserva-de-salas/index.php ou /reserva-de-salas/app/auth/login.php)
$script = $_SERVER['SCRIPT_NAME'] ?? '';

// Define a base da URL conforme o script em execução:
// - Script dentro de /app/ (ex: /reserva-de-salas/app/auth/login.php):
//   strpos() encontra '/app/' e substr() pega tudo antes → base = /reserva-de-salas
//   Assim, links e assets funcionam igual em qualquer página dentro de app/
// - Script na raiz (ex: /reserva-de-salas/index.php):
//   dirname($script) retorna o diretório do script → base = /reserva-de-salas
if (strpos($script, '/app/') !== false) {
    $baseUrl = substr($script, 0, strpos($script, '/app/'));
} else {
    $baseUrl = dirname($script);
}

// Normaliza: barras invertidas -> barras normais e remove barra final
$baseUrl = rtrim(str_replace('\\', '/', $baseUrl), '/');

// BASE_URL: raiz no navegador (ex: /reserva-de-salas ou '' se for na raiz do localhost)
define('BASE_URL', $baseUrl === '' ? '' : $baseUrl);

// URL da pasta de assets (ex: /reserva-de-salas/assets)
define('ASSETS_URL', BASE_URL . '/assets');
