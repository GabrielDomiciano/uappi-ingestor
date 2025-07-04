<?php

namespace Src\Http;

/**
 * Classe responsável por padronizar e gerenciar as respostas HTTP da aplicação.
 *
 * @author Gabriel Domiciano
 */
class ResponseHandler{
  /**
   * Método responsável por enviar uma resposta JSON e finaliza a execução do script.
   *
   * @param   array   $body         O corpo da resposta.
   * @param   int     $statusCode   O código de status HTTP.
   * @return  array                A resposta completa no formato esperado pela plataforma Serverless.
   */
  public function send(array $body, int $statusCode = 200): array{
    return [
      'statusCode'  => $statusCode,
      'headers'     => ['Content-Type' => 'application/json'],
      'body'        => json_encode($body)
    ];
  }

  /**
   * Método responsável por enviar uma resposta de erro padronizada.
   *
   * @param string  $message      A mensagem de erro.
   * @param int     $statusCode   O código de status HTTP.
   */
  public function sendError(string $message, int $statusCode = 400): array{
    return $this->send(['Message' => $message], $statusCode);
  }

  /**
   * Método responsável por tratar a resposta vinda da API da Uappi e envia a resposta apropriada para o cliente.
   * Ideal para operações de criação de recursos (POST).
   *
   * @param   array $uappiResponse  A resposta completa da API da Uappi, incluindo 'httpCode'.
   * @return  array                 A resposta estruturada para o cliente.
   */
  public function handleUappiResponse(array $uappiResponse): array{
    $httpCode = $uappiResponse['httpCode'] ?? 500;

    if ($httpCode >= 200 && $httpCode < 300) {
      $id = $uappiResponse['id'] ?? $uappiResponse['idProduto'] ?? null;
      return $this->send([
        'Id'      => $id,
        'Message' => 'Recurso criado com sucesso.',
      ], 201);
    } else {
      $errorMessage = $uappiResponse['error'] ?? 'Erro desconhecido ao processar a requisição.';

      if (stripos($errorMessage, 'já está cadastrado na plataforma') !== false || stripos($errorMessage, 'já cadastrado') !== false) {
        return $this->sendError($errorMessage, 409);
      } else {
        return $this->sendError($errorMessage, $httpCode);
      }
    }
  }
}
