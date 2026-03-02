<?php

/**
 * Front Controller - Aula 1 + Aula 3
 * Ponto único de entrada: recebe a requisição e encaminha para o Controller.
 */

// Carrega as constantes do projeto (BASE_PATH, APP_PATH, BASE_URL, etc.)
require_once __DIR__ . '/config/config.php';

// Aula 3: inicia a sessão para guardar o usuário logado. Deve vir antes de qualquer saída (echo, HTML).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexão com o banco: garante que $pdo exista antes dos controllers (Auth e Models usam).
require_once CONFIG_PATH . '/conexao.php';

// Registra o autoload para carregar Controllers e Models automaticamente
require_once APP_PATH . '/autoload.php';

// Funções auxiliares (urlIndex, exigirLogin) usadas pelos controllers
require_once APP_PATH . '/helpers.php';

// Lê da URL os parâmetros c=controller e a=action (ex: index.php?c=mapa&a=index)
// Se não forem passados, usa 'mapa' como controller e 'index' como action
$controllerName = $_GET['c'] ?? 'mapa';
$actionName     = $_GET['a'] ?? 'index';

// Converte o nome da rota no nome da classe do Controller
// Ex: minhas-reservas -> MinhasReservasController
$controllerName = str_replace('-', ' ', $controllerName);   // minhas-reservas -> minhas reservas
$controllerName = ucwords(strtolower($controllerName));      // minhas reservas -> Minhas Reservas
$controllerName = str_replace(' ', '', $controllerName) . 'Controller';  // Minhas Reservas -> MinhasReservasController

// Se a classe do controller não existir, retorna 404 e encerra
if (!class_exists($controllerName)) {
    http_response_code(404);
    echo 'Página não encontrada.';
    exit;
}

// Cria uma instância do controller (ex: new MapaController())
$controller = new $controllerName();
$action     = $actionName;

// Se o método (action) não existir no controller, retorna 404 e encerra
if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo 'Ação não encontrada.';
    exit;
}

// Chama o método do controller (ex: $controller->index())
$controller->$action();
