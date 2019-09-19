<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use NFePHP\Common\Certificate;
use NFePHP\DA\NFe\Danfe;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;

class NotaFiscalEletronicaServices
{
    private $config = [];

    private $service;

    public function __construct(OrderServices $service)
    {
        $this->service = $service;
    }

    public function show($id)
    {

        $order = $this->service->show($id);

        if ($order['xml']) {

            try {

                $xml = Storage::disk('s3')->get($order['xml']);

                if ($xml) {

                    $danfe = new Danfe($xml, 'P', 'A4', '', 'I', '');

                    $danfe->montaDANFE();

                    $render = $danfe->render();

                    return $render;

                } else {

                    return response()->json(
                        [
                            'message' => 'XML não encontrado'
                        ]
                    )->setStatusCode(500);

                }

            } catch (InvalidArgumentException $e) {
                return response()->json(
                    [
                        'message' => "Ocorreu um erro durante o processamento :" . $e->getMessage()
                    ]
                )->setStatusCode(500);
            }

        }

    }

    public function protocol($id)
    {

        $date = date('Y-m-d\TH:i:sT:00');

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

        $configJson = json_encode($this->config);

        $certificateDigital = Storage::disk('s3')->get($order['client']['company']['cert_file']);

        $tools = new Tools($configJson, Certificate::readPfx($certificateDigital, $order['client']['company']['cert_password']));

        $protocol = $tools->sefazConsultaRecibo($order['receipt']);

        $st = new Standardize();

        return ['data' => $st->toArray($protocol)];

    }

    public function delete($id)
    {

        $date = date('Y-m-d\TH:i:sT:00');

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

        $configJson = json_encode($this->config);

        $certificateDigital = Storage::disk('s3')->get($order['client']['company']['cert_file']);

        $tools = new Tools($configJson, Certificate::readPfx($certificateDigital, $order['client']['company']['cert_password']));

        $protocol = $tools->sefazConsultaRecibo($order['receipt']);
//        dd($protocol);
        $stdCl = new Standardize($protocol);

        $arr = $stdCl->toArray();

        $result = $tools->sefazCancela($arr['protNFe']['infProt']['chNFe'], 'TESTES', $arr['nRec']);

        dd($result);

    }

    public function store($id)
    {
        $date = date('Y-m-d\TH:i:sT:00');

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
        $std->tpNF = $order['tpNF'];
        $std->idDest = $order['idDest'];
        $std->cMunFG = $order['client']['company']['city']['id'];
        $std->tpImp = $order['tpImp'];
        $std->tpEmis = $order['tpEmis'];
        $std->cDV = '5';
        $std->tpAmb = '2';
        $std->finNFe = $order['finNFe'];
        $std->indFinal = $order['indFinal'];
        $std->indPres = $order['indPres'];
        $std->procEmi = '0'; //Emissão de NF-e com aplicativo do contribuinte. PODE DEIXAR FIXO
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
        $std->fone = preg_replace('/\D/', '', $order['client']['company']['phone']);

        $nfe->tagenderEmit($std);

        $std = new \stdClass();
        $std->xNome = $order['client']['title'];
        $std->CNPJ = $order['client']['cnpj']; //indicar apenas um CNPJ ou CPF ou idEstrangeiro
        $std->CPF = "";
        $std->IE = $order['client']['ie'];
        $std->indIEDest = $order['client']['indIEDest'];

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
        $std->fone = preg_replace('/\D/', '', $order['client']['phone']);

        $nfe->tagenderDest($std);

        $count = 1;

        $vProdTotal = 0;

        $vTotTribTotal = 0;

        foreach ($order['products'] as $key => $item) {

            $std = new \stdClass();
            $std->item = $count;
            $std->cProd = $item['id'];
            $std->cEAN = 'SEM GTIN';
            $std->xProd = $item['title'];
            $std->NCM = '68101900'; //Outros. DEIXAR FIXO
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
            $std->indTot = '1'; //DEIXAR FIXO

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
            $std->orig = '0'; // Origem da mercadoria: 0 - Nacional. DEIXAR FIXO
            $std->CSOSN = $item['icms'];

            $nfe->tagICMSSN($std);

            $std = new \stdClass();
            $std->item = $count;
            $std->cEnq = '999'; //DEIXAR FIXO
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

            $std = new \stdClass();
            $std->item = $count;
            $std->pesoL = $item['weigh'];
            $std->pesoB = $item['weigh'];

            $nfe->tagvol($std);

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
        $std->modFrete = $order['modFrete'];

        $nfe->tagtransp($std);

        $std = new \stdClass();
        $std->vTroco = 0.00; //aqui pode ter troco
        $nfe->tagpag($std);

        $std = new \stdClass();
        $std->indPag = $order['indPag'];
        $std->tPag = $order['tPag'];
        $std->vPag = $vProdTotal;

        $nfe->tagdetPag($std);

        $std = new \stdClass();
        $std->infCpl = 'DOCUMENTO EMITIDO POR ME OU EPP OPTANTE PELO SIMPLES NACIONAL NAO GERA DIREITO A CREDITO FISCAL DE ICMS E DE ISS E IPI CFE LEI 123/2006.';

        $nfe->taginfAdic($std);

        try {

            $xml = $nfe->getXML();

            $xmlKey = $nfe->getChave();

            if ($xmlKey) {

                $path_xml = 'xml/' . $xmlKey . '.xml';

                if (Storage::disk('s3')->put($path_xml, $xml)) {

                    $order->update([
                        'xml' => $path_xml
                    ]);

                    $configJson = json_encode($this->config);

                    $certificateDigital = Storage::disk('s3')->get($order['client']['company']['cert_file']);

                    $tools = new Tools($configJson, Certificate::readPfx($certificateDigital, $order['client']['company']['cert_password']));

                    try {

                        $xmlAssinado = $tools->signNFe($xml); // O conte�do do XML assinado fica armazenado na vari�vel $xmlAssinado

                        try {

                            $idLote = str_pad(rand(11111111111111, 999999999999999), 15, '0', STR_PAD_LEFT); // Identificador do lote

                            $resp = $tools->sefazEnviaLote([$xmlAssinado], $idLote);

                            $st = new Standardize();

                            $std = $st->toStd($resp);

                            if ($std->cStat != 103) {
                                return response()->json(
                                    [
                                        'message' => $std->cStat . ' - ' . $std->xMotivo
                                    ]
                                )->setStatusCode(500);
                            }

                            $receipt = $std->infRec->nRec;

//                        $protocol = $tools->sefazConsultaRecibo($receipt);
//
//                        $st = new Standardize();
//
//                        $std = $st->toStd($protocol);
//
//                        if ($std->protNFe->infProt->cStat != 103) {
//                            return response()->json(
//                                [
//                                    'message' => $std->protNFe->infProt->cStat . ' - ' . $std->protNFe->infProt->xMotivo
//                                ]
//                            )->setStatusCode(500);
//                        }

                            $order->update([
                                'receipt' => $receipt
                            ]);

                            return [
                                'receipt' => $receipt,
                                'xml' => $path_xml
                            ];

                        } catch (\Exception $e) {
                            return response()->json(
                                [
                                    'message' => 'Erro do envio: ' . $e->getMessage()
                                ]
                            )->setStatusCode(500);
                        }

                    } catch (\Exception $e) {
                        return response()->json(
                            [
                                'message' => 'Erro da assinatura: ' . $e->getMessage()
                            ]
                        )->setStatusCode(500);
                    }

                } else {
                    return response()->json(
                        [
                            'message' => 'XML não foi salvo no S3'
                        ]
                    )->setStatusCode(500);
                }

            } else {
                return response()->json(
                    [
                        'message' => 'Chave não encontrada'
                    ]
                )->setStatusCode(500);
            }

        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Erro ao gerar o xml: ' . $e->getMessage()
                ]
            )->setStatusCode(500);
        }

    }

}