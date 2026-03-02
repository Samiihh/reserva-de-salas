<?php

/**
 * Front Controller - Aula 1
 * Ponto único de entrada: recebe a requisição e encaminha para o Controller.
 */

// Carrega as constantes do projeto (BASE_PATH, APP_PATH, etc.)
require_once __DIR__ . '/config/config.php';

// Garante que a conexão PDO esteja disponível antes de qualquer Controller/Model
require_once CONFIG_PATH . '/conexao.php';

// Registra o autoload para carregar Controllers e Models automaticamente
require_once APP_PATH . '/autoload.php';

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
