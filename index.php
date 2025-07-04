<?php
/**
 * Este arquivo é um executor/roteador local para testar as funções serverless.
 * Ele NÃO deve ser incluído no build de produção.
 *
 * @author Gabriel Domiciano
 */
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . '/public' . $requestUri;

if(pathinfo($filePath, PATHINFO_EXTENSION) === '') {
  $filePath .= '.php';
}

//VERIFICA SE O ARQUIVO EXISTE
if (!file_exists($filePath)) {
  http_response_code(404);
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Endpoint não encontrado.']);
  exit;
}

require_once $filePath;

//VERIFICA SE A FUNÇÃO PRINCIPAL EXISTE
if (!function_exists('main')) {
  http_response_code(500);
  header('Content-Type: application/json');
  echo json_encode(['error' => 'A função principal (main) não foi encontrada no endpoint: ' . basename($filePath)]);
  exit;
}

//PREPARA OS DADOS DE REQUISIÇÃO
$requestBody  = file_get_contents('php://input');
$args         = json_decode($requestBody, true);
if (json_last_error() !== JSON_ERROR_NONE) {
  $args = [];
}

//EXECUTA A FUNÇÃO PRINCIPAL
$response = main($args);

//TRADUZ PARA UMA RESPOSTA HTTP
http_response_code($response['statusCode'] ?? 500);
if (isset($response['headers'])) {
  foreach ($response['headers'] as $key => $value) {
    header("$key: $value");
  }
}

echo $response['body'] ?? '';
