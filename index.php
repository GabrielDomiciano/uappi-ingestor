<?php

// Carrega o autoloader do Composer
require __DIR__ . '/vendor/autoload.php';

// Carrega as variáveis de ambiente do .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Obtém o caminho da requisição (ex: /vtex/products/create.php)
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Constrói o caminho completo do arquivo na pasta public
$filePath = __DIR__ . '/public' . $requestUri;

// Verifica se o arquivo existe e é um arquivo PHP
if (file_exists($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
  // Inclui o arquivo PHP solicitado
  require $filePath;
} else {
  // Retorna 404 se o arquivo não for encontrado
  http_response_code(404);
  echo '404 Not Found';
}