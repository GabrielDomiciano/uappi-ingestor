<?php

namespace Src\Http;

/**
 * Classe responsável por padronizar e gerenciar as respostas HTTP da aplicação.
 * Define o Content-Type, o código de status e o corpo da resposta em JSON.
 *
 * @author Gabriel Domiciano
 */
class ResponseHandler{
  /**
   * Método Construtor da classe.
   * Define o cabeçalho Content-Type como application/json para todas as respostas.
   */
  public function __construct(){
    header('Content-Type: application/json');
  }

  /**
   * Método responsável por enviar uma resposta JSON e finaliza a execução do script.
   *
   * @param array   $data         O array de dados a ser convertido para JSON.
   * @param int     $statusCode   O código de status HTTP a ser enviado.
   */
  public function send(array $data, int $statusCode = 200): void{
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
  }

  /**
   * Método responsável por enviar uma resposta de erro padronizada.
   *
   * @param string  $message      A mensagem de erro.
   * @param int     $statusCode   O código de status HTTP.
   */
  public function sendError(string $message, int $statusCode = 400): void{
    $this->send(['Message' => $message], $statusCode);
  }

  /**
   * Método responsável por tratar a resposta vinda da API da Uappi e envia a resposta apropriada para o cliente.
   * Ideal para operações de criação de recursos (POST).
   *
   * @param array $uappiResponse A resposta completa da API da Uappi, incluindo 'httpCode'.
   */
  public function handleUappiResponse(array $uappiResponse): void{
    $httpCode = $uappiResponse['httpCode'] ?? 500;

    if ($httpCode >= 200 && $httpCode < 300) {
      $id = $uappiResponse['id'] ?? $uappiResponse['idProduto'] ?? null;
      $this->send([
        'Id'      => $id,
        'Message' => 'Recurso criado com sucesso.',
      ], 201);
    } else {
      $errorMessage = $uappiResponse['error'] ?? 'Erro desconhecido ao processar a requisição.';

      if (stripos($errorMessage, 'já está cadastrado na plataforma') !== false || stripos($errorMessage, 'já cadastrado') !== false) {
        $this->sendError($errorMessage, 409);
      } else {
        $this->sendError($errorMessage, $httpCode);
      }
    }
  }
}
