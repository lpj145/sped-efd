<?php
/**
 * Created by PhpStorm.
 * User: Marcos
 * Date: 21/02/2017
 * Time: 15:34
 */

require __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Marcos\Spedefd;

$sped = new Spedefd();
$sped->codVersao = '009';
$sped->cnpjContribuente = '14268975000100';
$sped->peridoInicial = '31122016';
$sped->peridoFinal = '31122016';
$sped->razaoContribuente = 'Sebastião Marcos Amorim Dantas';
$sped->endContribuente = 'Rua Sete de setembro';
$sped->nomeContador = 'Andre Pereira de Azevedo';
$sped->adicionarUnidadeMedida('UN', 'UNIDADE');
$sped->adicionarUnidadeMedida('UNID', 'UNIDADE');
$sped->adicionarUnidadeMedida('UND', 'UNIDADE');
$sped->adicionarProdutoServico(
    '0001', 'Item de cor marrom', '', '', 'UN', 200, '00', '28092800', '', '', '', '', ''  
);
$sped->adicionarInventario('0001','UN','20','39,90', '80.2');
$sped->adicionarInventario('0009', 'UN', '20', '46.92', '187.93');
$sped->adicionarInventario('0008', 'UN', '20', '39.20', '900.42');
$sped->dataInventario->setDate(2016,12,31);
echo $sped;
