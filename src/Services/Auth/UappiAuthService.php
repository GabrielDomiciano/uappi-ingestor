<?php

namespace Src\Services\Auth;

use Src\Http\RequestHandler;

/**
 * Classe responsável por gerenciar a autenticação com a API da Uappi.
 *
 * @author Gabriel Domiciano
 */
class UappiAuthService {
  /**
   * Responsável por armazenar o endpoint da API da Uappi.
   *
   * @var string
   */
  private string $endpoint;

  /**
   * Responsável por armazenar a chave da API para autenticação.
   *
   * @var string
   */
  private string $apiKey;

  /**
   * Responsável por armazenar a chave secreta para autenticação.
   *
   * @var string
   */
  private string $secretKey;

  /**
   * Responsável por manipular requisições HTTP.
   *
   * @var RequestHandler
   */
  private RequestHandler $requestHandler;

  /**
   * Método responsável por construir a classe com as credenciais da API.
   *
   * @param string $endpoint    O endpoint da API da Uappi.
   * @param string $apiKey      A chave da API para autenticação.
   * @param string $secretKey   A chave secreta para autenticação.
   */
  public function __construct(string $endpoint, string $apiKey, string $secretKey){
    $this->endpoint         = $endpoint;
    $this->apiKey           = $apiKey;
    $this->secretKey        = $secretKey;
    $this->requestHandler   = new RequestHandler();
  }

  /**
   * Método responsável por obter um token de autenticação válido.
   * Se o token em cache for válido, ele é retornado. Caso contrário, um novo token é gerado.
   *
   * @return string       O token de autenticação.
   * @throws \Exception   Se houver um erro ao gerar o token.
   */
  public function getToken(): string {
    return $this->generateToken();
  }

  /**
   * Método responsável por forçar a geração de um novo token, ignorando o cache.
   *
   * @return string     O novo token de autenticação.
   * @throws \Exception Se houver um erro ao gerar o token.
   */
  public function forceNewToken(): string{
    return $this->generateToken();
  }

  /**
   * Método responsável por gerar um novo token de autenticação junto à API da Uappi.
   *
   * @return string     O novo token de autenticação.
   * @throws \Exception Se a resposta da API for inválida ou contiver erros.
   */
  private function generateToken(): string{
    $url      = "{$this->endpoint}/auth";
    $payload  = ['apiKey' => $this->apiKey, 'secretKey' => $this->secretKey];
    $headers  = ['Content-Type: application/json', 'App-Token: wapstore'];

    $response = $this->requestHandler->send('POST', $url, $payload, $headers);

    $body     = $response['body'];
    $httpCode = $response['httpCode'];

    if($httpCode !== 201 || !isset($body['token'])) {
      throw new \Exception('Erro ao gerar token: ' . ($body['error'] ?? 'Token não recebido'));
    }

    return $body['token'];
  }
}
