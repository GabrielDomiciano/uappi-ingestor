<?php

use Src\Http\ResponseHandler;
use Src\Vtex\Products\Mappers\VtexToUappiProductMapper;
use Src\Services\Products\UappiProductService;

/**
 * Função principal para a criação de produtos na plataforma Serverless.
 *
 * @param array $args   Argumentos da requisição (corpo, headers, etc.).
 * @return array        A resposta HTTP estruturada.
 *
 * @author Gabriel Domiciano
 */
function main(array $args): array {
  $responseHandler = new ResponseHandler();

  $vtexPayload = $args;

  if(empty($vtexPayload)) {
    return $responseHandler->sendError('Payload da VTEX ausente', 400);
  }

  $uappiPayload = (new VtexToUappiProductMapper())->map($vtexPayload);
  $uappiResponse = (new UappiProductService())->handler($uappiPayload);

  return $responseHandler->handleUappiResponse($uappiResponse);
}