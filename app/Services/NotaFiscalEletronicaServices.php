<?php

namespace App\Services;

use NFePHP\NFe\Make;

class NotaFiscalEletronicaServices
{
    private $config = [];

    private $service;

    public function __construct(OrderServices $service)
    {
        $this->service = $service;
    }

    public function gerarNota($id)
    {
        $date = date('Y-m-d\TH:i:s');

        $order = $this->service->show($id);

        $this->config = [
            "atualizacao" => $date,
            "tpAmb" => 2, // Se deixar o tpAmb como 2 você emitir a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
            "razaosocial" => $order['client']['company']['title'],
            "siglaUF" => $order['client']['company']['state']['abbr'],
            "cnpj" => $order['client']['company']['cnpj'],
            "schemes" => "PL_008i2",
            "versao" => "4.00",
            "tokenIBPT" => "AAAAAAA"
        ];

        $nfe = new Make();

        $std = new \stdClass();
        $std->versao = '4.00';
        $std->Id = null;
        $std->pk_nItem = '';
        $nfe->taginfNFe($std);

        $std = new \stdClass();
        $std->cUF = $order['client']['company']['state']['id'];
        $std->cNF = '51004416';
        $std->natOp = '5.101 Venda de producao do estabelecimento';
        $std->mod = '55';
        $std->serie = '1';
        $std->nNF = $order['id'];
        $std->dhEmi = $date;
        $std->dhSaiEnt = $date;
        $std->tpNF = '1';
        $std->idDest = '1';
        $std->cMunFG = $order['client']['company']['city']['id'];
        $std->tpImp = '1';
        $std->tpEmis = '1';
        $std->cDV = '5';
        $std->tpAmb = '2';
        $std->finNFe = '1';
        $std->indFinal = '0';
        $std->indPres = '1';
        $std->procEmi = '0'; //Emissão de NF-e com aplicativo do contribuinte.
        $std->verProc = 'RocketzWeb 1.0';

        $nfe->tagide($std);

        $std = new \stdClass();
        $std->xNome = $order['client']['company']['title'];
        $std->IE = $order['client']['company']['ie'];
        $std->CRT = 1;
        $std->CNPJ = $order['client']['company']['cnpj'];
        $std->CPF = ""; //indicar apenas um CNPJ ou CPF

        $nfe->tagemit($std);

        $std = new \stdClass();
        $std->xLgr = $order['client']['company']['address'];
        $std->nro = $order['client']['company']['number'];
        $std->xBairro = $order['client']['company']['neighborhood'];
        $std->cMun = $order['client']['company']['city']['id'];
        $std->xMun = $order['client']['company']['city']['name'];
        $std->UF = $order['client']['company']['state']['abbr'];
        $std->CEP = $order['client']['company']['cep'];
        $std->cPais = '1058';
        $std->xPais = 'BRASIL';
        $std->fone = $order['client']['company']['phone'];

        $nfe->tagenderEmit($std);

        $std = new \stdClass();
        $std->xNome = $order['client']['title'];
        $std->CNPJ = $order['client']['cnpj']; //indicar apenas um CNPJ ou CPF ou idEstrangeiro
        $std->CPF = "";
        $std->IE = $order['client']['ie'];
        $std->indIEDest = 1;

        $nfe->tagdest($std);

        $std = new \stdClass();
        $std->xLgr = $order['client']['address'];
        $std->nro = $order['client']['number'];
        $std->xBairro = $order['client']['neighborhood'];
        $std->cMun = $order['client']['city']['id'];
        $std->xMun = $order['client']['city']['name'];
        $std->UF = $order['client']['state']['abbr'];
        $std->CEP = $order['client']['cep'];
        $std->cPais = '1058';
        $std->xPais = 'BRASIL';
        $std->fone = $order['client']['phone'];

        $nfe->tagenderDest($std);

        $count = 1;

        $vProdTotal = 0;

        $vTotTribTotal = 0;

        $vPeso = 0;

        foreach ($order['products'] as $key => $item) {

            $vPeso += $item['weigh'];

            $std = new \stdClass();
            $std->item = $count;
            $std->cProd = '133';
            $std->cEAN = 'SEM GTIN';
            $std->xProd = $item['title'];
            $std->NCM = '68101900';
            $std->CEST = '0000000';
            $std->CFOP = $item['cfop'];
            $std->uCom = $item['ucom'];
            $std->qCom = $item['pivot']['quantity'];
            $std->vUnCom = $item['pivot']['price'];

            $vProd = $std->vProd = $item['pivot']['price'] * $item['pivot']['quantity'];

            $vProdTotal += $vProd;

            $std->cEANTrib = 'SEM GTIN';
            $std->uTrib = $item['ucom'];
            $std->qTrib = $item['pivot']['quantity'];
            $std->vUnTrib = $item['pivot']['price'];
            $std->indTot = '1';

            $nfe->tagprod($std);

            $std = new \stdClass();
            $std->item = $count; //item da NFe
            $std->CEST = '0000000';

            $nfe->tagCEST($std);

            $std = new \stdClass();

            $std->item = $count;

            $std->vTotTrib = 0;

            $vTotTrib = 0;

            $vTotTrib += ($order['client']['company']['cofins'] * $vProd) / 100;

            $vTotTrib += ($order['client']['company']['pis'] * $vProd) / 100;

            $vTotTrib += ($order['client']['company']['irpj'] * $vProd) / 100;

            $vTotTrib += ($order['client']['company']['csll'] * $vProd) / 100;

            $vTotTrib += ($order['client']['company']['iss'] * $vProd) / 100;

            $std->vTotTrib = $vTotTrib;

            $vTotTribTotal += $vTotTrib;

            $nfe->tagimposto($std);

            $std = new \stdClass();
            $std->item = $count;
            $std->orig = '0';
            $std->CSOSN = $item['icms'];

            $nfe->tagICMSSN($std);

            $std = new \stdClass();
            $std->item = $count;
            $std->cEnq = '999';
            $std->CST = $item['ipi'];

            $nfe->tagIPI($std);

            $std = new \stdClass();
            $std->item = $count;
            $std->CST = $item['pis'];

            $nfe->tagPIS($std);

            $std = new \stdClass();
            $std->item = $count;
            $std->CST = $item['cofins'];

            $nfe->tagCOFINS($std);

            $count++;
        }

        $std = new \stdClass();
        $std->vBC = 0.00;
        $std->vICMS = 0.00;
        $std->vICMSDeson = 0.00;
        $std->vFCP = 0.00;
        $std->vBCST = 0.00;
        $std->vST = 0.00;
        $std->vFCPST = 0.00;
        $std->vFCPSTRet = 0.00;
        $std->vProd = $vProdTotal;
        $std->vFrete = 0.00;
        $std->vSeg = 0.00;
        $std->vDesc = 0.00;
        $std->vII = 0.00;
        $std->vIPI = 0.00;
        $std->vIPIDevol = 0.00;
        $std->vPIS = 0.00;
        $std->vCOFINS = 0.00;
        $std->vOutro = 0.00;
        $std->vNF = $vProdTotal;
        $std->vTotTrib = $vTotTribTotal;

        $nfe->tagICMSTot($std);

        $std = new \stdClass();
        $std->modFrete = 0;

        $nfe->tagtransp($std);

        $std = new \stdClass();
        $std->item = 1;
        $std->pesoL = $vPeso;
        $std->pesoB = $vPeso;

        $nfe->tagvol($std);

        $std = new \stdClass();
        $std->vTroco = 0.00; //aqui pode ter troco
        $nfe->tagpag($std);

        $std = new \stdClass();
        $std->indPag = 0; //0= Pagamento à Vista 1= Pagamento à Prazo
        $std->tPag = '01';
        $std->vPag = $vProdTotal;

        $nfe->tagdetPag($std);

        $std = new \stdClass();
        $std->infCpl = 'DOCUMENTO EMITIDO POR ME OU EPP OPTANTE DOCUMENTO EMITIDO POR ME OU EPP OPTANTE PELO SIMMPLES NACIONAL NAO GERA DIREITO A CREDITO FISCAL DE ICMS E DE ISS E IPI CFE LEI 123/2006.';

        $nfe->taginfAdic($std);

        $xml = $nfe->getXML();
        die($xml);

//        $configJson = json_encode($this->config);
//
//        $certificadoDigital = Storage::disk('s3')->get('certs/iKaEeMEMBqe3Zcqe6UqTe5uqRgtJZgIekso04lHc.bin');
//
//        $tools = new Tools($configJson, Certificate::readPfx($certificadoDigital, '96265851'));
//
//        try {
//
//            $xmlAssinado = $tools->signNFe($xml); // O conte�do do XML assinado fica armazenado na vari�vel $xmlAssinado
//
//            try {
//
//                $idLote = str_pad(rand(11111111111111, 999999999999999), 15, '0', STR_PAD_LEFT); // Identificador do lote
//
//                $resp = $tools->sefazEnviaLote([$xmlAssinado], $idLote);
//
//                $st = new Standardize();
//
//                $std = $st->toStd($resp);
//
//                if ($std->cStat != 103) {
//                    //erro registrar e voltar
//                    exit("[$std->cStat] $std->xMotivo");
//                }
//
//                $recibo = $std->infRec->nRec; // Vamos usar a vari�vel $recibo para consultar o status da nota
//
//                $protocolo = $tools->sefazConsultaRecibo($recibo);
//
//                dd($protocolo);
//
//            } catch (\Exception $e) {
//                //aqui voc� trata possiveis exceptions do envio
//                var_dump('aqui voc� trata possiveis exceptions do envio');
//                exit($e->getMessage());
//            }
//
//        } catch (\Exception $e) {
//            //aqui voc� trata poss�veis exceptions da assinatura
//            var_dump('aqui voc� trata poss�veis exceptions da assinatura');
//            exit($e->getMessage());
//        }

    }

}