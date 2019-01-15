<?php
/**
 * Created by PhpStorm.
 * User: Marcos
 * Date: 21/02/2017
 * Time: 16:08
 */

namespace Marcos;


class Spedefd
{
    /**
     * @var Código de versão do layout conforme ato COTEPE
     */
    public $codVersao;
    /**
     * @var int Finalidade do arquivo
     * 0 - Arquivo Original
     * 1 - Remessa para subistuição de arquivo
     */
    public $codFin = 0;
    /**
     * @var string Insira a data em modo texto sem nenhum carater, apenas texto
     * ex: 31022017
     */
    public $periodoInicial = '';
    /**
     * @var string Insira a data em modo texto sem nenhum carater, apenas texto
     * ex: 31022017
     */
    public $periodoFinal = '';
    /**
     * @var Cnpj do contribuente
     */
    public $cnpjContribuente = '';
    /**
     * @var Razao Social
     */
    public $razaoContribuente = '';
    /**
     * @var Cpf do responsável dono da empresa
     */
    public $cpfContribuente = '';
    /**
     * @var UF duas letras somente, apenas sigla
     */
    public $ufContribuente = '';
    /**
     * @var Inscrição Estadual Contribuente
     */
    public $ieContribuente;
    /**
     * @var Codigo de Municio do contribuente
     */
    public $codMunContribuente;
    /**
     * @var Inscrição municipal
     */
    public $imContribuente;
    /**
     * @var Pra que serve?
     */
    public $suframa;
    /**
     * @var Indicador de atividade
     * A - Perfil A
     * B - Perfil B
     * C - Perfil C
     */
    public $indPerfil;
    /**
     * @var Indicador de atividade
     * 0 - Industrial ou equiparado a industria
     * 1 - Outros
     */
    public $indAtividade;

    /**
     * @var int Informações Complementares
     * Caso atribua 0 sera necessario o preenchimento conforme manual
     */
    public $infComplementares = 1;

    public $fantasiaContribuente = '';
    public $cepContribuente = '';
    public $endContribuente = '';
    public $numContribuente = '';
    public $complementoContribuente = '';
    public $bairroContribuente = '';
    public $foneContribuente = '';
    public $faxContribuente = '';
    public $emailContribuente = '';

    /**
     * Informações do contador
     */
    public $nomeContador = '';
    public $cpfContador = '';
    public $crcContador = '';
    /**
     * @var string Cnpj do escritorio de contabilidade
     */
    public $cnpjContador = '';
    public $cepContador = '';
    public $endContador = '';
    public $numContador = '';
    public $complContador = '';
    public $bairroContador = '';
    public $foneContador = '';
    public $faxContador = '';
    public $emailContador = '';
    public $codigoMunContador = '';

    /**
     * Informações de produtos
     */

    /**
     * @var array Definição da unidade
     * Para uso, deve se definir uma chave e depois um valor
     * chave corresponde a unidade e valor a descrição
     */
    public $unidadesMedida = [];
    /**
     * @var array Produtos do registro 0200
     */
    private $produto = [];
    /**
     * @var array Produtos do registro H010
     */
    private $itensiventario = [];
    public $dataInventario;
    /**
     * Registro de blocos
     * H05 => 10
     * @var array
     */
    private $tiposRegistros = [];

    private $text = '';
    private $textLenght = 0;
    private $totalLinhas = 0;

    public function __construct()
    {
        $this->dataInventario = new \DateTime();
    }

    /**
     * Ao adicionar o cabeçalho, toda a string é composta pelos argumentos superiores
     * @param string $codRegistro
     * @param array ...$tags
     * @return string
     */
    private function textTag($codRegistro = '', ...$tags)
    {
        if (!array_key_exists($codRegistro, $this->tiposRegistros)) {
            $this->tiposRegistros[$codRegistro] = 0;
        }
        $argLength = sizeof($tags);
        $text = '|'.$codRegistro.'|';
        for ($i = 0; $i < $argLength; $i++) {
            $text .= $tags[$i] . '|';
        }
        $this->textLenght++;
        $this->totalLinhas++;
        $this->tiposRegistros[$codRegistro] += 1;
        return $text.PHP_EOL;
    }

    /**
     * Identificação do arquivo sped
     * @return string 0000
     */
    protected function identificar()
    {
        return $this->text = $this->textTag('0000',
            $this->codVersao,
            $this->codFin,
            $this->periodoInicial,
            $this->periodoFinal,
            $this->razaoContribuente,
            $this->cnpjContribuente,
            $this->cpfContribuente,
            $this->ufContribuente,
            $this->ieContribuente,
            $this->codMunContribuente,
            $this->imContribuente,
            $this->suframa,
            $this->indPerfil,
            $this->indAtividade
            );
    }

    /**
     * Gera bloco 0005 e 0001 com base na informaçao complementar
     * @return um ou dois blocos com dados complementar
     */
    protected function identificarComplementar()
    {
        if ($this->endContribuente != '') {
            $this->text .= $this->textTag('0001', 0);
            $this->text .= $this->textTag('0005',
                $this->fantasiaContribuente,
                $this->cepContribuente,
                $this->endContribuente,
                $this->numContribuente,
                $this->complementoContribuente,
                $this->bairroContribuente,
                $this->foneContribuente,
                $this->faxContribuente,
                $this->emailContribuente
            );
        } else {
            $this->text .= $this->textTag('0001', 1);
        }
        return $this->text;
    }

    /**
     * Identificar contador, sendo obrigatorio a todos os sped
     * @return string
     */
    protected function identificarContador()
    {
        if ($this->nomeContador != '') {
            $this->text .= $this->textTag('0100',
                $this->nomeContador,
                $this->cpfContador,
                $this->crcContador,
                $this->cnpjContador,
                $this->cepContador,
                $this->endContador,
                $this->numContador,
                $this->complContador,
                $this->bairroContador,
                $this->foneContador,
                $this->faxContador,
                $this->emailContador,
                $this->codigoMunContador);
        }
        return $this->text;
    }

    /**
     * Adiciona ao bloco unidade de medida
     * @return bloco 0190
     */
    protected function identificarUnidades()
    {
        $unidadesLength = sizeof($this->unidadesMedida);
        foreach ($this->unidadesMedida as $unidade) {
            $this->text .= $this->textTag(
                '0190',
                $unidade['unid'],
                $unidade['desc']
            );
        }
        return $this->text;
    }

    /**
     * Identifica unidades de medida no registro
     * @param string $unid
     * @param string $desc
     * @return array
     */
    public function adicionarUnidadeMedida($unid = '', $desc = '')
    {
        if ($unid != '') {
            $this->unidadesMedida[$unid] = [
                'unid' => $unid,
                'desc' => $desc
                ];
        }
        return $this->unidadesMedida;
    }

    /**
     * Adiciona bloco com produto ou serviço
     * identificado ao tipo de unidade
     * @return bloco 0200
     */
    private function identificarProdutoseServicos()
    {
        $produtosLength = sizeof($this->produto);
        for ($i = 0; $i < $produtosLength; $i++) {
            $this->text .= $this->textTag('0200',
                $this->produto[$i]['cod_item'],
                $this->produto[$i]['desc_item'],
                $this->produto[$i]['cod_barra'],
                $this->produto[$i]['cod_ant_item'],
                $this->produto[$i]['unid_inv'],
                $this->produto[$i]['tipo_item'],
                $this->produto[$i]['cod_ncm'],
                $this->produto[$i]['ex_ipi'],
                $this->produto[$i]['cod_gen'],
                $this->produto[$i]['cod_lst'],
                $this->produto[$i]['aliq_icms'],
                $this->produto[$i]['cest'] ?? '0000000'
                );
            $this->text .= $this->textTag('0220',
                $this->produto[$i]['unid_inv'],
                1
            );
        }
        return $this->text;
    }

    /**
     * Adiciona produto ou serviço
     * @param string $cod_item
     * @param string $desc_item
     * @param string $cod_barra
     * @param string $cod_ant_item
     * @param string $unid_inv
     * @param int $qnt_item
     * @param string $tipo_item
     * @param string $cod_ncm
     * @param string $ex_ipi
     * @param string $cod_gen
     * @param string $cod_lst
     * @param string $aliq_icms
     * @param $cest
     * @return array de produtos e servicos
     */
    public function adicionarProdutoServico(
        $cod_item = '',
        $desc_item = '',
        $cod_barra = '',
        $cod_ant_item = '',
        $unid_inv = '',
        $tipo_item = '',
        $cod_ncm = '',
        $ex_ipi = '',
        $cod_gen = '',
        $cod_lst = '',
        $aliq_icms = '',
        $cest
    )
    {
        $this->produto[] = [
          'cod_item' => $cod_item,
            'desc_item' => $desc_item,
            'cod_barra' => $cod_barra,
            'cod_ant_item' => $cod_ant_item,
            'unid_inv' => $unid_inv,
            'tipo_item' => $tipo_item,
            'cod_ncm' => $cod_ncm,
            'ex_ipi' => $ex_ipi,
            'cod_gen' => $cod_gen,
            'cod_lst' => $cod_lst,
            'aliq_icms' => $aliq_icms,
            'cest' => $cest
        ];
        return $this->produto;
    }

    /**
     * Identifica o iventario e retorna de acordo com as informaçoes prestadas
     * necessita utilizar a funcao adicionarInventario
     * Essa instruçao abre e fecha o bloco contendo linhas e valores totais.
     * @return string contendo o bloco H
     */
    private function identificaInventario()
    {
        $iventarioLength = sizeof($this->itensiventario);
        $text = '';
        $valorTotal = 0;
        $this->textLenght = 0;
        for ($i = 0; $i < $iventarioLength; $i++) {
            $text .= $this->textTag('H010',
                $this->itensiventario[$i]['cod_item'],
                $this->itensiventario[$i]['unid'],
                number_format($this->itensiventario[$i]['qtd'], 3, ',', ''),
                number_format($this->itensiventario[$i]['vl_unit'], 6, ',', ''),
                number_format($this->itensiventario[$i]['vl_item'], 2, ',', ''),
                $this->itensiventario[$i]['ind_prop'],
                $this->itensiventario[$i]['cod_part'],
                $this->itensiventario[$i]['txt_compl'],
                $this->itensiventario[$i]['cod_cta'],
                $this->itensiventario[$i]['vl_item_ir']
                );
            $valorTotal += (float)$this->itensiventario[$i]['vl_item'];
        }
        $valorTotal = number_format($valorTotal, '2', ',', '');
        if ($iventarioLength > 0) {
            $this->text .= $this->textTag('H001', 0);
            $this->text .= $this->textTag('H005', $this->dataInventario->format('dmY'), $valorTotal, '01');
            $this->text .= $text;
            $this->text .= $this->textTag('H990', $this->textLenght+1);
        } else {
            $this->text .= $this->textTag('H001', 1);
            $this->text .= $this->textTag('H990', 2);
        }
        $this->textLenght = 0;
        return $this->text;
    }

    public function adicionarInventario(
        $cod_item = '',
        $unid = '',
        $qtd = '',
        $vl_unit = '',
        $vl_item = '',
        $ind_prop = '',
        $cod_part = '',
        $txt_compl = '',
        $cod_cta = '',
        $vl_item_ir = ''
    )
    {
        $this->itensiventario[] = [
          'cod_item' => $cod_item,
            'unid' => $unid,
            'qtd' => $qtd,
            'vl_unit' => $vl_unit,
            'vl_item' => $vl_item,
            'ind_prop' => $ind_prop,
            'cod_part' => $cod_part,
            'txt_compl' => $txt_compl,
            'cod_cta' => $cod_cta,
            'vl_item_ir' => $vl_item_ir
        ];
        return $this->text;
    }

    private function encerrabloco0()
    {
        $this->text .= $this->textTag('0990', $this->textLenght+1);
        $this->textLenght = 0;
        return $this->text;
    }

    /**
     * Bloco c falta implementar
     * @return string
     */
    private function abreeencerraBlococ()
    {
        $this->text .= $this->textTag('C001', 1);
        $this->text .= $this->textTag('C990', 2);

        return $this->text;
    }

    /**
     * Bloco d falta implementar
     * @return string
     */
    private function abreeencerraBlocod()
    {
        $this->text .= $this->textTag('D001', 1);
        $this->text .= $this->textTag('D990', 2);
        return $this->text;
    }

    private function abreeencerraBlocoe()
    {
        $this->text .= $this->textTag('E001', '0');
        $this->text .= $this->textTag('E100', $this->periodoInicial, $this->periodoFinal);
        $this->text .= $this->textTag('E110', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ,0);
        $this->text .= $this->textTag('E116', '000', '0', $this->periodoFinal, '1210', '', '', '', '', '122015');
        $this->text .= $this->textTag('E990', '5');

    }

    private function abreEEncerraBlocoK()
    {
        $this->text .= $this->textTag('K001', '1');
        $this->text .= $this->textTag('K990', '2');
    }

    private function abreencerraBlocog()
    {
        $this->text .= $this->textTag('G001', '1');
        $this->text .= $this->textTag('G990', '2');
    }

    private function abreencerrabloco01()
    {
        $this->text .= $this->textTag('1001', '0');
        $this->text .= $this->textTag('1010', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
        $this->text .= $this->textTag('1990', '3');
        $this->textLenght = 0;
        return $this->text;
    }

    private function abreecerraBloco09()
    {
        $this->text .= $this->textTag('9001', '0');
        foreach ($this->tiposRegistros as $key => $tipoRegistro) {
            $this->text .= $this->textTag('9900', $key, $tipoRegistro);
        }
        $this->text .= $this->textTag('9900', '9900', $this->textLenght + 2);
        $this->text .= $this->textTag('9900', '9990', '01');
        $this->text .= $this->textTag('9900', '9999', '01');
        $this->text .= $this->textTag('9990', $this->textLenght + 2);
        $this->text .= $this->textTag('9999', (substr_count( $this->text, "\n" ) + 1));
        return $this->text;
    }

    /**
     * Retorna todo o conteudo formatado
     * @return string
     */
    public function __toString()
    {
        $this->identificar();
        $this->identificarComplementar();
        $this->identificarContador();
        $this->identificarUnidades();
        $this->identificarProdutoseServicos();
        $this->encerrabloco0();
        $this->abreeencerraBlococ();
        $this->abreeencerraBlocod();
        $this->abreeencerraBlocoe();
        $this->abreencerraBlocog();
        $this->identificaInventario();
        $this->abreEEncerraBlocoK();
        $this->abreencerrabloco01();
        $this->abreecerraBloco09();

        return $this->text;
    }
}