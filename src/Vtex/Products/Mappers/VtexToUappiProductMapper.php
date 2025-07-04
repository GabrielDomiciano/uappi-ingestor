<?php

namespace Src\Vtex\Products\Mappers;

/**
 * Classe responsável por mapear dados de produtos da Vtex para o formato da Uappi.
 *
 * @author Gabriel Domiciano
 */
class VtexToUappiProductMapper{
  /**
   * Método responsável por mapear um produto da Vtex para a Uappi
   *
   * @param   array $vtex
   * @return  array
   */
  public function map(array $vtex): array{
    return [
      'idCategoria'          => $vtex['CategoryId'] ?? null,
      'idMarca'              => $vtex['BrandId'] ?? null,
      'idProdutoLider'       => 0,
      'nome'                 => $vtex['Name'] ?? '',
      'adicionalNome'        => '',
      'sku'                  => $vtex['RefId'] ?? '',
      'ativo'                => $vtex['IsActive'] ?? true,
      'venda'                => true,
      'servico'              => false,
      'assinatura'           => false,
      'aparecerSite'         => $vtex['IsVisible'] ?? true,
      'aparecerBusca'        => true,
      'aparecerXml'          => false,
      'sincronizarApi'       => false,
      'sincronizarHub'       => true,
      'precos'               => [
        'precoDe'          => $vtex['PriceFrom']    ?? 0.01,
        'precoPor'         => $vtex['Price']        ?? 0.01,
        'precoCusto'       => $vtex['CostPrice']    ?? 0,
        'precoEspecial'    => $vtex['SpecialPrice'] ?? 0,
        'editarPreco'      => false,
        'dadosDescontoVista' => [
          'descontoGeral'    => 0,
          'descontoBoleto'   => 0,
          'descontoPix'      => 0,
          'descontoDeposito' => 0,
          'descontoCartao'   => 0,
        ]
      ],
      'descricaoCurta'       => $vtex['DescriptionShort'] ?? '',
      'descricao'            => $vtex['Description'] ?? '',
      'tabelaMedida'         => '',
      'video'                => '',
      'dimensoes'            => [
        'altura'      => $vtex['Height']    ?? 0.1,
        'largura'     => $vtex['Width']     ?? 0.1,
        'comprimento' => $vtex['Length']    ?? 0.1,
        'peso'        => $vtex['WeightKg']  ?? 0.001,
      ],
      'prazoProducao'        => 0,
      'prazoFornecedor'      => 0,
      'vendaSemEstoque'      => false,
      'busca'                => $vtex['KeyWords'] ?? '',
      'googleDescription'    => $vtex['MetaTagDescription'] ?? '',
      'googleCondition'      => '',
      'googleAgeGroup'       => '',
      'googleGender'         => '',
      'ncm'                  => '',
      'mpn'                  => '',
      'ean'                  => $vtex['Ean'] ?? '',
      'exclusivo'            => false,
      'categoriasAdicionais' => [],
      'landingPages'         => [],
      'filtros'              => [],
      'caracteristicas'      => (object)[],
    ];
  }
}
