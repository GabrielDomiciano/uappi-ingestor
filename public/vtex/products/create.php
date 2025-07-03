<?php

use Src\Vtex\Products\Mappers\VtexToUappiProductMapper;
use Src\Services\Products\UappiProductService;

$data     = json_decode(file_get_contents('php://input'), true);
$produto  = (new VtexToUappiProductMapper())->map($data);
$resposta = (new UappiProductService())->handler($produto);

echo json_encode($resposta);
