<?php

namespace Src\Services;

use Src\Services\Auth\UappiAuthService;

/**
 * Classe abstrata responsável por fornecer a base para os serviços da Uappi.
 * Gerencia a comunicação com a API e a autenticação.
 *
 * @author Gabriel Domiciano
 */
abstract class AbstractUappiService {
  /**
   * Responsável por armazenar o endpoint da API da Uappi.
   *
   * @var string
   */
  protected string $endpoint;

  /**
   * Responsável por gerenciar a autenticação com a API da Uappi.
   *
   * @var UappiAuthService
   */
  protected UappiAuthService $authService;

  /**
   * Método responsável por construir a classe e inicializar o serviço de autenticação.
   */
  public function __construct() {
    $this->endpoint = $_ENV['UAPPI_ENDPOINT'];
    $this->authService = new UappiAuthService(
      $_ENV['UAPPI_ENDPOINT'],
      $_ENV['UAPPI_API_KEY'],
      $_ENV['UAPPI_SECRET_KEY']
    );
  }

  /**
   * Método responsável por manipular os dados
   *
   * @param array $data
   * @return array
   */
  abstract public function handler(array $data): array;

  /**
   * Método responsável por enviar uma requisição para a API da Uappi.
   * Inclui lógica de reautenticação em caso de token inválido (HTTP 401).
   *
   * @param string $method    O método HTTP (GET, POST, PUT, DELETE).
   * @param string $endpoint  O endpoint específico da API.
   * @param array  $data      Os dados a serem enviados na requisição.
   * @return array            A resposta da API, incluindo o código HTTP.
   */
  protected function sendRequest(string $method, string $endpoint, array $data = []): array {
    $token = $this->authService->getToken();
    $response = $this->doRequest($method, $endpoint, $data, $token);

    if (isset($response['error']) && $response['error'] === 'Token inválido.') {
      // Se o token for inválido, força a geração de um novo e tenta novamente
      $token = $this->authService->forceNewToken();
      $response = $this->doRequest($method, $endpoint, $data, $token);
    }

    return $response;
  }

  /**
   * Método responsável por executar a requisição HTTP real.
   *
   * @param string  $method    O método HTTP.
   * @param string  $endpoint  O endpoint da API.
   * @param array   $data      Os dados da requisição.
   * @param string  $token     O token de autenticação a ser usado.
   * @return array             A resposta decodificada da API, incluindo o código HTTP.
   */
  private function doRequest(string $method, string $endpoint, array $data, string $token): array{
    $ch = curl_init($endpoint);

    $headers = [
        'Content-Type: application/json',
        'App-Token: wapstore',
        'Authorization: Bearer ' . $token,
    ];

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_POSTFIELDS     => json_encode($data),
        CURLOPT_HTTPHEADER     => $headers
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        return ['error' => curl_error($ch)];
    }

    curl_close($ch);
    $decodedResponse = json_decode($response, true) ?? ['error' => 'Resposta inválida'];
    $decodedResponse['httpCode'] = $httpCode;

    return $decodedResponse;
  }
}


