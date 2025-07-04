<?php

use Src\Http\ResponseHandler;
use Src\Vtex\Products\Mappers\VtexToUappiProductMapper;
use Src\Services\Products\UappiProductService;

$responseHandler = new ResponseHandler();

$vtexPayload = json_decode(file_get_contents('php://input'), true);
if(empty($vtexPayload)) {
 $responseHandler->sendError('Payload da VTEX ausente', 400);
}

$uappiPayload   = (new VtexToUappiProductMapper())->map($vtexPayload);
$uappiResponse  = (new UappiProductService())->handler($uappiPayload);

$responseHandler->handleUappiResponse($uappiResponse);
