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

    private $nfe;

    public function __construct(OrderServices $service)
    {
        $this->config = [
            "atualizacao" => date('Y-m-d\TH:i:sT:00'),
            "tpAmb"       => 2, // Se deixar o tpAmb como 2 você emitir a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
            "schemes"     => "PL_008i2",
            "versao"      => "4.00",
            "tokenIBPT"   => "AAAAAAA"
        ];
        $this->service = $service;
    }

    public function show($id)
    {

        $order = $this->service->show($id);

        if ($order['xml']) {

            try {

                $xml = Storage::disk('s3')->get($order['xml']);

                if ($xml) {

                    $image = '';

                    if ($order['client']['company']['image']) {
                        $image = getenv('AWS_URL_PUBLIC') . $order['client']['company']['image'];
                    }

                    $data = new Danfe($xml, 'P', 'A4', $image, 'I', '');

                    $data->montaDANFE();

                    return $data->render();

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

    private function setOrder($id)
    {

        $this->order = $this->service->show($id);

        $this->config["razaosocial"] = $this->order['client']['company']['title'];
        $this->config["siglaUF"] = $this->order['client']['company']['state']['abbr'];
        $this->config["cnpj"] = $this->order['client']['company']['cnpj'];
    }

    public function protocol($id)
    {

        $this->setOrder($id);

        $certificateDigital = Storage::disk('s3')->get($this->order['client']['company']['cert_file']);

        $tools = new Tools(json_encode($this->config), Certificate::readPfx($certificateDigital, $this->order['client']['company']['cert_password']));

        $protocol = $tools->sefazConsultaRecibo($this->order['receipt']);

        $st = new Standardize();

        return ['data' => $st->toArray($protocol)];

    }

    public function delete($id)
    {

        $this->setOrder($id);

        $certificateDigital = Storage::disk('s3')->get($this->order['client']['company']['cert_file']);

        $tools = new Tools(json_encode($this->config), Certificate::readPfx($certificateDigital, $this->order['client']['company']['cert_password']));

        $protocol = $tools->sefazConsultaRecibo($this->order['receipt']);

        $stdCl = new Standardize($protocol);

        $arr = $stdCl->toArray();

        $result = $tools->sefazCancela($arr['protNFe']['infProt']['chNFe'], 'TESTES', $arr['nRec']);

        dd($result);

    }

    public function store($id)
    {

        $this->setOrder($id);

        $this->nfe = new Make();

        $this->taginfNFe();

        $this->tagide();

        $this->tagemit();

        $this->tagenderEmit();

        $this->tagdest();

        $this->tagenderDest();

        $count = 1;

        $vProdTotal = 0;

        $vTotTribTotal = 0;

        foreach ($this->order['products'] as $key => $item) {

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

            $this->nfe->tagprod($std);

            $std = new \stdClass();
            $std->item = $count; //item da NFe
            $std->CEST = '0000000';

            $this->nfe->tagCEST($std);

            $std = new \stdClass();

            $std->item = $count;

            $std->vTotTrib = 0;

            $vTotTrib = 0;

            $vTotTrib += ($this->order['client']['company']['cofins'] * $vProd) / 100;

            $vTotTrib += ($this->order['client']['company']['pis'] * $vProd) / 100;

            $vTotTrib += ($this->order['client']['company']['irpj'] * $vProd) / 100;

            $vTotTrib += ($this->order['client']['company']['csll'] * $vProd) / 100;

            $vTotTrib += ($this->order['client']['company']['iss'] * $vProd) / 100;

            $std->vTotTrib = $vTotTrib;

            $vTotTribTotal += $vTotTrib;

            $this->nfe->tagimposto($std);

            $std = new \stdClass();
            $std->item = $count;
            $std->orig = '0'; // Origem da mercadoria: 0 - Nacional. DEIXAR FIXO
            $std->CSOSN = $item['icms'];

            $this->nfe->tagICMSSN($std);

            $std = new \stdClass();
            $std->item = $count;
            $std->cEnq = '999'; //DEIXAR FIXO
            $std->CST = $item['ipi'];

            $this->nfe->tagIPI($std);

            $std = new \stdClass();
            $std->item = $count;
            $std->CST = $item['pis'];

            $this->nfe->tagPIS($std);

            $std = new \stdClass();
            $std->item = $count;
            $std->CST = $item['cofins'];

            $this->nfe->tagCOFINS($std);

            $std = new \stdClass();
            $std->item = $count;
            $std->pesoL = $item['weigh'];
            $std->pesoB = $item['weigh'];

            $this->nfe->tagvol($std);

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

        $this->nfe->tagICMSTot($std);

        $this->tagtransp();

        $this->tagpag();

        $std = new \stdClass();
        $std->indPag = $this->order['indPag'];
        $std->tPag = $this->order['tPag'];
        $std->vPag = $vProdTotal;

        $this->nfe->tagdetPag($std);

        $this->taginfAdic();

        try {

            $xml = $this->nfe->getXML();

            $xmlKey = $this->nfe->getChave();

            if ($xmlKey) {

                $path_xml = 'xml/' . $xmlKey . '.xml';

                if (Storage::disk('s3')->put($path_xml, $xml)) {

                    $this->order->update([
                        'xml' => $path_xml
                    ]);

                    $configJson = json_encode($this->config);

                    $certificateDigital = Storage::disk('s3')->get($this->order['client']['company']['cert_file']);

                    $tools = new Tools($configJson, Certificate::readPfx($certificateDigital, $this->order['client']['company']['cert_password']));

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

                            $this->order->update([
                                'receipt' => $receipt
                            ]);

                            return [
                                'receipt' => $receipt,
                                'xml'     => $path_xml
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

    private function taginfNFe()
    {
        $std = new \stdClass();
        $std->versao = '4.00';
        $std->Id = null;
        $std->pk_nItem = '';
        $this->nfe->taginfNFe($std);
    }

    private function tagide()
    {
        $std = new \stdClass();
        $std->cUF = $this->order['client']['company']['state']['id'];
        $std->cNF = '51004416';
        $std->natOp = '5.101 Venda de producao do estabelecimento';
        $std->mod = '55';
        $std->serie = '1';
        $std->nNF = $this->order['id'];
        $std->dhEmi = date('Y-m-d\TH:i:sT:00');
        $std->dhSaiEnt = date('Y-m-d\TH:i:sT:00');
        $std->tpNF = $this->order['tpNF'];
        $std->idDest = $this->order['idDest'];
        $std->cMunFG = $this->order['client']['company']['city']['id'];
        $std->tpImp = $this->order['tpImp'];
        $std->tpEmis = $this->order['tpEmis'];
        $std->cDV = '5';
        $std->tpAmb = '2';
        $std->finNFe = $this->order['finNFe'];
        $std->indFinal = $this->order['indFinal'];
        $std->indPres = $this->order['indPres'];
        $std->procEmi = '0'; //Emissão de NF-e com aplicativo do contribuinte. PODE DEIXAR FIXO
        $std->verProc = 'RocketzWeb 1.0';

        $this->nfe->tagide($std);
    }

    private function tagemit()
    {
        $std = new \stdClass();
        $std->xNome = $this->order['client']['company']['title'];
        $std->IE = $this->order['client']['company']['ie'];
        $std->CRT = 1;
        $std->CNPJ = $this->order['client']['company']['cnpj'];
        $std->CPF = ""; //indicar apenas um CNPJ ou CPF

        $this->nfe->tagemit($std);
    }

    private function tagenderEmit()
    {
        $std = new \stdClass();
        $std->xLgr = $this->order['client']['company']['address'];
        $std->nro = $this->order['client']['company']['number'];
        $std->xBairro = $this->order['client']['company']['neighborhood'];
        $std->cMun = $this->order['client']['company']['city']['id'];
        $std->xMun = $this->order['client']['company']['city']['name'];
        $std->UF = $this->order['client']['company']['state']['abbr'];
        $std->CEP = $this->order['client']['company']['cep'];
        $std->cPais = '1058';
        $std->xPais = 'BRASIL';
        $std->fone = preg_replace('/\D/', '', $this->order['client']['company']['phone']);

        $this->nfe->tagenderEmit($std);
    }

    private function tagdest()
    {
        $std = new \stdClass();
        $std->xNome = $this->order['client']['title'];
        $std->CNPJ = $this->order['client']['cnpj']; //indicar apenas um CNPJ ou CPF ou idEstrangeiro
        $std->CPF = "";
        $std->IE = $this->order['client']['ie'];
        $std->indIEDest = $this->order['client']['indIEDest'];

        $this->nfe->tagdest($std);
    }

    private function tagenderDest()
    {
        $std = new \stdClass();
        $std->xLgr = $this->order['client']['address'];
        $std->nro = $this->order['client']['number'];
        $std->xBairro = $this->order['client']['neighborhood'];
        $std->cMun = $this->order['client']['city']['id'];
        $std->xMun = $this->order['client']['city']['name'];
        $std->UF = $this->order['client']['state']['abbr'];
        $std->CEP = $this->order['client']['cep'];
        $std->cPais = '1058';
        $std->xPais = 'BRASIL';
        $std->fone = preg_replace('/\D/', '', $this->order['client']['phone']);

        $this->nfe->tagenderDest($std);
    }

    private function tagtransp()
    {
        $std = new \stdClass();
        $std->modFrete = $this->order['modFrete'];

        $this->nfe->tagtransp($std);
    }

    private function tagpag()
    {
        $std = new \stdClass();
        $std->vTroco = 0.00; //aqui pode ter troco

        $this->nfe->tagpag($std);
    }

    private function taginfAdic()
    {
        $std = new \stdClass();
        $std->infCpl = 'DOCUMENTO EMITIDO POR ME OU EPP OPTANTE PELO SIMPLES NACIONAL NAO GERA DIREITO A CREDITO FISCAL DE ICMS E DE ISS E IPI CFE LEI 123/2006.';

        $this->nfe->taginfAdic($std);
    }

}