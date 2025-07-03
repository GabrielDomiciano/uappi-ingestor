<?php

namespace Src\Services\Products;

use Src\Services\AbstractUappiService;

/**
 * Classe responsável por manipular operações relacionadas a produtos na API da Uappi.
 *
 * @author Gabriel Domiciano
 */
class UappiProductService extends AbstractUappiService {
  /**
   * Método responsável por criar um produto na Uappi
   *
   * @param array $product
   * @return array
   */
  public function handler(array $product): array {
    return $this->sendRequest('POST', "{$this->endpoint}/products", $product);
  }
}
