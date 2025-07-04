<?php

namespace Src\Http;

/**
 * Classe responsável por manipular e executar requisições HTTP utilizando cURL.
 * Encapsula a complexidade do cURL para fornecer uma interface simples e reutilizável.
 *
 * @author Gabriel Domiciano
 */
class RequestHandler{
  /**
   * Método responsável por enviar uma requisição HTTP para a URL especificada.
   *
   * @param string  $method       O método HTTP (ex: 'POST', 'GET', 'PUT').
   * @param string  $url          A URL completa para a qual a requisição será enviada.
   * @param array   $data         Os dados a serem enviados no corpo da requisição (serão codificados como JSON).
   * @param array   $headers      Um array de cabeçalhos HTTP.
   * @return array                Um array contendo o corpo da resposta decodificado e o código de status HTTP.
   */
  public function send(string $method, string $url, array $data = [], array $headers = []): array{
    $ch = curl_init($url);

    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST  => $method,
      CURLOPT_POSTFIELDS     => json_encode($data),
      CURLOPT_HTTPHEADER     => $headers
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
      return [
        'body' => ['error' => curl_error($ch)],
        'httpCode' => 500
      ];
    }

    curl_close($ch);

    $decodedResponse = json_decode($response, true) ?? ['error' => 'Resposta inválida ou corpo vazio.'];

    return [
      'body' => $decodedResponse,
      'httpCode' => $httpCode
    ];
  }
}
