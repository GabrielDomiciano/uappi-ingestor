<?php

namespace Src\Services;

use Src\Services\Auth\UappiAuthService;
use Src\Http\RequestHandler;

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
   * Responsável por manipular requisições HTTP.
   *
   * @var RequestHandler
   */
  protected RequestHandler $requestHandler;

  /**
   * Construtor da classe.
   */
  public function __construct(){
    $this->endpoint       = $_ENV['UAPPI_ENDPOINT'];
    $this->authService    = new UappiAuthService(
      $_ENV['UAPPI_ENDPOINT'],
      $_ENV['UAPPI_API_KEY'],
      $_ENV['UAPPI_SECRET_KEY']
    );
    $this->requestHandler = new RequestHandler();
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
   * @param string $url       A URL completa do endpoint.
   * @param array $data       Os dados a serem enviados.
   * @return array            A resposta da API, incluindo corpo e código HTTP.
   */
  protected function sendRequest(string $method, string $url, array $data = []): array{
    $token    = $this->authService->getToken();
    $response = $this->doRequest($method, $url, $data, $token);

    if ($response['httpCode'] === 401 || $response['error'] === 'Token inválido') {
      $token    = $this->authService->forceNewToken();
      $response = $this->doRequest($method, $url, $data, $token);
    }

    return array_merge($response['body'], ['httpCode' => $response['httpCode']]);
  }

  /**
   * Método responsável por executar a requisição HTTP real.
   *
   * @param string $method  O método HTTP.
   * @param string $url     A URL do endpoint.
   * @param array  $data    Os dados da requisição.
   * @param string $token   O token de autenticação.
   * @return array          A resposta da API (corpo e código HTTP).
   */
  private function doRequest(string $method, string $url, array $data, string $token): array{
    $headers = [
      'Content-Type: application/json',
      'App-Token: wapstore',
      'Authorization: Bearer ' . $token,
    ];

    return $this->requestHandler->send($method, $url, $data, $headers);
  }
}
