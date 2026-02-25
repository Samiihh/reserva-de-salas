<?php

/**
 * Autoload simples - Aula 1
 * Procura a classe em Controllers e Models pelo nome da classe.
 */

// Registra uma função de autoload que o PHP chama automaticamente
// quando uma classe é usada (ex: new MeuController) mas ainda não foi carregada
spl_autoload_register(function ($className) {

    // Monta o caminho base onde ficam as pastas de classes (ex: C:\...\app\)
    $basePath = APP_PATH . DIRECTORY_SEPARATOR;

    // Pastas onde o autoload vai procurar os arquivos das classes
    $folders = ['Controllers', 'Models'];

    // Percorre cada pasta (Controllers e Models)
    foreach ($folders as $folder) {

        // Monta o caminho completo do arquivo: basePath + pasta + nomeDaClasse.php
        // Ex: app/Controllers/LoginController.php ou app/Models/Usuario.php
        $path = $basePath . $folder . DIRECTORY_SEPARATOR . $className . '.php';

        // Se o arquivo existir nessa pasta, carrega e encerra a busca
        if (file_exists($path)) {
            require_once $path;  // Inclui o arquivo da classe uma única vez
            return;              // Para aqui; não precisa procurar nas outras pastas
        }
    }
});
