<?php

use Src\Http\ResponseHandler;
use Src\Vtex\Products\Mappers\VtexToUappiProductVariationMapper;
use Src\Services\Products\UappiProductVariationService;

/**
 * Função responsável por lidar com a atualização de variações de produtos da VTEX.
 * 
 * @author Gabriel Domiciano
 */
function main(array $args): array {
  $responseHandler = new ResponseHandler();

  try {
    $vtexPayload = $args;
    if (empty($vtexPayload) || !isset($vtexPayload['ProductId'])) {
      return $responseHandler->sendError('Payload da VTEX inválido ou ausente.', 400);
    }

    $mapper = new VtexToUappiProductVariationMapper();
    $uappiPayload = $mapper->map($vtexPayload);

    $service = new UappiProductVariationService();
    $uappiResponse = $service->handler($uappiPayload);

    return $responseHandler->handleUappiResponse($uappiResponse);

  } catch (\Exception $e) {
    return $responseHandler->sendError('Erro ao processar a variação: ' . $e->getMessage(), 500);
  }
}
