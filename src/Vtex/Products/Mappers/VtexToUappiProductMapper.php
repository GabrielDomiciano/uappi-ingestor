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
      'idCategoria'       => $vtex['CategoryId'] ?? null,
      // 'codigoCategoria'   => null,
      'idMarca'           => $vtex['BrandId'] ?? null,
      // 'codigoMarca'       => null,
      'idProdutoLider'    => 0,
      'nome'              => $vtex['Name'] ?? '',
      'adicionalNome'     => '',
      'sku'               => $vtex['RefId'] ?? '',
      'ativo'             => $vtex['IsActive'] ?? true,
      'venda'             => true,
      'servico'           => false,
      'assinatura'        => false,
      'aparecerSite'      => $vtex['IsVisible'] ?? true,
      'aparecerBusca'     => true,
      'aparecerXml'       => false,
      'sincronizarApi'    => false,
      'sincronizarHub'    => false,
      'precos' => [
        'precoDe'          => 10,
        'precoPor'         => 10,
        'precoCusto'       => 0,
        'precoEspecial'    => 0,
        'editarPreco'      => false,
        'dadosDescontoVista' => [
          'descontoGeral'   => 0,
          'descontoBoleto'  => 0,
          'descontoPix'     => 0,
          'descontoDeposito' => 0,
          'descontoCartao'  => 0,
        ]
      ],
      'descricaoCurta'    => $vtex['DescriptionShort'] ?? '',
      'descricao'         => $vtex['Description'] ?? '',
      'video'             => '',
      'dimensoes' => [
        'altura'     => $vtex['Height'] ?? 0,
        'largura'    => $vtex['Width'] ?? 0,
        'comprimento' => $vtex['Length'] ?? 0,
        'peso'       => $vtex['WeightKg'] ?? 1,
      ],
      'prazoProducao'     => 0,
      'prazoFornecedor'   => 0,
      'vendaSemEstoque'   => true,
      'busca'             => $vtex['KeyWords'] ?? '',
      'googleDescription' => $vtex['MetaTagDescription'] ?? '',
      'googleCondition'   => '',
      'googleAgeGroup'    => '',
      'googleGender'      => '',
      'ncm'               => '',
      'mpn'               => '',
      'qrcode'            => '',
      'exclusivo'         => false,
      'categoriasAdicionais' => [],
      'filtros'              => [],
      // 'idAtributoUnico'      => null
    ];
  }
}
