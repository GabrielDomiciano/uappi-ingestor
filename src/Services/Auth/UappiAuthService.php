<?php

namespace Src\Services\Auth;

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
   * Método responsável por construir a classe com as credenciais da API.
   *
   * @param string $endpoint    O endpoint da API da Uappi.
   * @param string $apiKey      A chave da API para autenticação.
   * @param string $secretKey   A chave secreta para autenticação.
   */
  public function __construct(string $endpoint, string $apiKey, string $secretKey) {
    $this->endpoint = $endpoint;
    $this->apiKey = $apiKey;
    $this->secretKey = $secretKey;
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
   * Método responsável por gerar um novo token de autenticação junto à API da Uappi.
   *
   * @return string     O novo token de autenticação.
   * @throws \Exception Se houver um erro na requisição ou na resposta da API.
   */
  private function generateToken(): string {
    $ch = curl_init("{$this->endpoint}/auth");

    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => json_encode([
        'apiKey'    => $this->apiKey,
        'secretKey' => $this->secretKey
      ]),
      CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'App-Token: wapstore',
      ]
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
      throw new \Exception('Erro ao gerar token: ' . curl_error($ch));
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if(isset($data['error']) || !isset($data['token'])) {
      throw new \Exception('Erro ao gerar token: ' . ($data['error'] ?? 'Token não recebido'));
    }

    

    return $data['token'];
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
}
