<?php

namespace App\Services;

use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;

class NotaFiscalEletronicaServices
{
    private $config = [];

    public function __construct()
    {
        $this->config = [
            "atualizacao" => "2019-08-23T13:48:00-02:00",
            "tpAmb" => 2, // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
            "razaosocial" => "Empresa teste",
            "siglaUF" => "RS",
            "cnpj" => "78767865000156",
            "schemes" => "PL_008i2",
            "versao" => "4.00",
            "tokenIBPT" => "AAAAAAA"
        ];
    }

    public function gerarNota()
    {


        $nfe = new Make();

        $std = new \stdClass();
        $std->versao = '4.00';
        $std->Id = null;
        $std->pk_nItem = '';
        $nfe->taginfNFe($std);

        $std = new \stdClass();
        $std->cUF = 43;
        $std->cNF = '80070008';
        $std->natOp = 'VENDA';

        $std->indPag = 0; //NÃO EXISTE MAIS NA VERSÃO 4.00

        $std->mod = 55;
        $std->serie = 1;
        $std->nNF = 2;
        $std->dhEmi = '2019-08-23T13:48:00-02:00';
        $std->dhSaiEnt = null;
        $std->tpNF = 1;
        $std->idDest = 1;
        $std->cMunFG = 3518800;
        $std->tpImp = 1;
        $std->tpEmis = 1;
        $std->cDV = 2;
        $std->tpAmb = 2;
        $std->finNFe = 1;
        $std->indFinal = 0;
        $std->indPres = 0;
        $std->procEmi = 0;
        $std->verProc = '3.10.31';
        $std->dhCont = null;
        $std->xJust = null;

        $nfe->tagide($std);

        $std = new \stdClass();
        $std->vTroco = null; //incluso no layout 4.00, obrigatório informar para NFCe (65)
        $nfe->tagpag($std);

        $std = new \stdClass();
        $std->tPag = '03';
        $std->vPag = 200.00; //Obs: deve ser informado o valor pago pelo cliente
        $std->CNPJ = '12345678901234';
        $std->tBand = '01';
        $std->cAut = '3333333';
        $std->tpIntegra = 1; //incluso na NT 2015/002
        $std->indPag = '0'; //0= Pagamento à Vista 1= Pagamento à Prazo
        $nfe->tagdetPag($std);

        $std = new \stdClass();
        $std->xNome = "Jose alencar";
        $std->xFant = "JOJO AL";
        $std->IE = "ISENTO";
        $std->IEST = 32;
        $std->IM = "";
        $std->CNAE = "";
        $std->CRT = 3;
        $std->CNPJ = ""; //indicar apenas um CNPJ ou CPF
        $std->CPF = "484.712.360-36";

        $nfe->tagemit($std);

        $std = new \stdClass();
        $std->xLgr = "Rua Teste";
        $std->nro = '203';
        $std->xBairro = 'Centro';
        $std->cMun = 3506003; //Código de município precisa ser válido e igual o  cMunFG
        $std->xMun = 'Bauru';
        $std->UF = 'SP';
        $std->CEP = '80045190';
        $std->cPais = '1058';
        $std->xPais = 'BRASIL';
        $nfe->tagenderEmit($std);

        $std = new \stdClass();
        $std->xNome = "";
        $std->indIEDest = 1;
        $std->IE = "ISENTO";
        $std->ISUF = "";
        $std->IM = "";
        $std->email = "";
        $std->CNPJ = ""; //indicar apenas um CNPJ ou CPF ou idEstrangeiro
        $std->CPF = "484.712.360-36";
        $std->idEstrangeiro = null;

        $nfe->tagdest($std);

        $std = new \stdClass();
        $std->xLgr = "Sousa filho";
        $std->nro = 32;
        $std->xCpl = "Casa";
        $std->xBairro = "Jose sil";
        $std->cMun = 1234568;
        $std->xMun = "dsa dsa";
        $std->UF = "RS";
        $std->CEP = "93347085";
        $std->cPais = "";
        $std->xPais = "";
        $std->fone = "";

        $nfe->tagenderDest($std);

        $std = new \stdClass();
        $std->item = 1; //item da NFe
        $std->cProd = "CFOP9999";
        $std->cEAN = "SEM GTIN";
        $std->xProd = "POSTE DE LUX";
        $std->NCM = "00";

        $std->cBenef = ""; //incluido no layout 4.00

        $std->EXTIPI = "";
        $std->CFOP = "6110";
        $std->uCom = "QUAL";
        $std->qCom = 0;
        $std->vUnCom = "12.00";
        $std->vProd = "1.00";
        $std->cEANTrib = "SEM GTIN";
        $std->uTrib = "12";
        $std->qTrib = "12";
        $std->vUnTrib = "12";
        $std->vFrete = "";
        $std->vSeg = "";
        $std->vDesc = "";
        $std->vOutro = "";
        $std->indTot = 0;
        $std->xPed = "";
        $std->nItemPed = "";
        $std->nFCI = "";

        $nfe->tagprod($std);

        $std = new \stdClass();
        $std->item = 1; //item da NFe
        $std->vTotTrib = 1000.00;

        $nfe->tagimposto($std);

        $std = new \stdClass();
        $std->item = 1;
        $std->orig = 0;
        $std->CST = '00';
        $std->modBC = 0;
        $std->vBC = '0.20';
        $std->pICMS = '18.0000';
        $std->vICMS = '0.04';
        $nfe->tagICMS($std);

        $std = new \stdClass();
        $std->item = 1; //item da NFe
        $std->clEnq = null;
        $std->CNPJProd = null;
        $std->cSelo = null;
        $std->qSelo = null;
        $std->cEnq = '999';
        $std->CST = '50';
        $std->vIPI = 150.00;
        $std->vBC = 1000.00;
        $std->pIPI = 15.00;
        $std->qUnid = null;
        $std->vUnid = null;

        $nfe->tagIPI($std);

        $std = new \stdClass();
        $std->item = 1; //item da NFe
        $std->CST = '07';
        $std->vBC = null;
        $std->pPIS = null;
        $std->vPIS = null;
        $std->qBCProd = null;
        $std->vAliqProd = null;

        $nfe->tagPIS($std);

        $std = new \stdClass();
        $std->item = 1; //item da NFe
        $std->vCOFINS = 289.30;
        $std->vBC = 2893.00;
        $std->pCOFINS = 10.00;
        $std->qBCProd = null;
        $std->vAliqProd = null;

        $nfe->tagCOFINSST($std);

        $std = new \stdClass();
        $std->vBC = 1000.00;
        $std->vICMS = 1000.00;
        $std->vICMSDeson = 1000.00;
        $std->vFCP = 1000.00; //incluso no layout 4.00
        $std->vBCST = 1000.00;
        $std->vST = 1000.00;
        $std->vFCPST = 1000.00; //incluso no layout 4.00
        $std->vFCPSTRet = 1000.00; //incluso no layout 4.00
        $std->vProd = 1000.00;
        $std->vFrete = 1000.00;
        $std->vSeg = 1000.00;
        $std->vDesc = 1000.00;
        $std->vII = 1000.00;
        $std->vIPI = 1000.00;
        $std->vIPIDevol = 1000.00; //incluso no layout 4.00
        $std->vPIS = 1000.00;
        $std->vCOFINS = 1000.00;
        $std->vOutro = 1000.00;
        $std->vNF = 1000.00;
        $std->vTotTrib = 1000.00;

        $nfe->tagICMSTot($std);

        $std = new \stdClass();
        $std->modFrete = 1;

        $nfe->tagtransp($std);

        $std = new \stdClass();
        $std->item = 1; //indicativo do numero do volume
        $std->qVol = 2;
        $std->esp = 'caixa';
        $std->marca = 'OLX';
        $std->nVol = '11111';
        $std->pesoL = 10.50;
        $std->pesoB = 11.00;

        $nfe->tagvol($std);

        $std = new \stdClass();
        $std->nFat = '1233';
        $std->vOrig = 1254.22;
        $std->vDesc = null;
        $std->vLiq = 1254.22;

        $nfe->tagfat($std);

        $std = new \stdClass();
        $std->nDup = '1233-1';
        $std->dVenc = '2017-08-22';
        $std->vDup = 1254.22;

        $nfe->tagdup($std);

        $xml = $nfe->getXML();

        $configJson = json_encode($this->config);

        $certificadoDigital = file_get_contents(public_path('storage/certificado.pfx'));

        $tools = new Tools($configJson, Certificate::readPfx($certificadoDigital, '96265851'));

        try {

            $xmlAssinado = $tools->signNFe($xml); // O conteúdo do XML assinado fica armazenado na variável $xmlAssinado

            try {

                $idLote = str_pad(rand(11111111111111, 999999999999999), 15, '0', STR_PAD_LEFT); // Identificador do lote

                $resp = $tools->sefazEnviaLote([$xmlAssinado], $idLote);

                $st = new Standardize();

                $std = $st->toStd($resp);

                if ($std->cStat != 103) {
                    //erro registrar e voltar
                    exit("[$std->cStat] $std->xMotivo");
                }

                $recibo = $std->infRec->nRec; // Vamos usar a variável $recibo para consultar o status da nota

                $protocolo = $tools->sefazConsultaRecibo($recibo);

                dd($protocolo);

            } catch (\Exception $e) {
                //aqui você trata possiveis exceptions do envio
                var_dump('aqui você trata possiveis exceptions do envio');
                exit($e->getMessage());
            }

        } catch (\Exception $e) {
            //aqui você trata possíveis exceptions da assinatura
            var_dump('aqui você trata possíveis exceptions da assinatura');
            exit($e->getMessage());
        }

    }


}