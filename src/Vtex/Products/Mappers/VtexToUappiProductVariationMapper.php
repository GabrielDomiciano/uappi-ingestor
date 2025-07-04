<?php

namespace Src\Vtex\Products\Mappers;

use Exception;

/**
 * Classe responsável por mapear dados de variações de produtos da Vtex para o formato Uappi.
 * 
 * @author Gabriel Domiciano
 */
class VtexToUappiProductVariationMapper {
  /**
   * Array que contém os mapeamentos de atributos da Vtex para o formato Uappi.
   *
   * @var array
   */
  private array $mappings;

  /**
   * Método responsável por construir o mapeador, carregando as configurações de mapeamento.
   */
  public function __construct() {
    $this->mappings = [
      "cor" => [
        "preto" => 17
      ],
      "tamanho" => [
        "41" => 63
      ]
    ];
  }

  /**
   * Método responsável por mapear os dados de uma variação de produto da Vtex para o formato Uappi.
   * @param   array $vtexData   Os dados da variação de produto da Vtex.
   * @return  array             Os dados da variação de produto no formato Uappi.
   * @throws  Exception         Se um mapeamento não for encontrado para o atributo.
   */
  public function map(array $vtexData): array {
    $variationName = strtolower($vtexData['Name']); // "41 preto"
    
    $idAtributoUnico    = $this->findMapping('cor', $variationName);
    $idAtributoSimples  = $this->findMapping('tamanho', $variationName);

    return [
      'idProduto' => $vtexData['ProductId'],
      'payload' => [
        'idAtributoUnico' => $idAtributoUnico,
        'atributosSimples' => [
          [
            'id' => $idAtributoSimples,
            'sku' => $vtexData['RefId'],
            'ean' => $vtexData['Ean'] ?? '',
            'precos' => [
              'precoDefault' => true,
              'precoDe'   => 0,
              'precoPor'  => 0
            ],
            'dimensoes' => [
              'dimensaoDefault' => false,
              'altura'          => $vtexData['PackagedHeight'],
              'largura'         => $vtexData['PackagedWidth'],
              'comprimento'     => $vtexData['PackagedLength'],
              'peso'            => $vtexData['PackagedWeightKg'],
            ]
          ]
        ]
      ]
    ];
  }

  /**
   * Método responsável por encontrar o ID de mapeamento para um tipo de atributo e nome de variação.
   * @param   string $attributeType   O tipo de atributo (ex: 'cor', 'tamanho').
   * @param   string $variationName   O nome da variação (ex: '41 preto').
   * @return  int                     O ID do mapeamento.
   * @throws  Exception               Se o tipo de atributo não for encontrado ou nenhum mapeamento for encontrado.
   */
  private function findMapping(string $attributeType, string $variationName): int {
    if (!isset($this->mappings[$attributeType])) {
      throw new Exception("Tipo de atributo '{$attributeType}' não encontrado no mapeamento.");
    }

    foreach ($this->mappings[$attributeType] as $name => $id) {
      if (strpos($variationName, strtolower($name)) !== false) {
        return $id;
      }
    }

    throw new Exception("Nenhum mapeamento encontrado para o atributo '{$attributeType}' no nome '{$variationName}'.");
  }
}
