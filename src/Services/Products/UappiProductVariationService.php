<?php

namespace Src\Services\Products;

use Src\Services\AbstractUappiService;

/**
 * Classe responsável por atualizar uma variação de produto na Uappi.
 *
 * @author Gabriel Domiciano
 */
class UappiProductVariationService extends AbstractUappiService{
  /**
   * Método responsável por atualizar uma variação de produto na Uappi.
   *
   * @param   array $data
   * @return  array
   */
  public function handler(array $data): array{
    $productId  = $data['idProduto'];
    $payload    = $data['payload'];

    $url = "{$this->endpoint}/products/{$productId}";
    
    return $this->sendRequest('PUT', $url, $payload);
  }
}
