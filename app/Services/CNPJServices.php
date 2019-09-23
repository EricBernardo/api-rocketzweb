<?php

namespace App\Services;

class CNPJServices extends DefaultServices
{

    public function show($request)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.receitaws.com.br/v1/cnpj/" . preg_replace('/\D/', '', $request->get('cnpj')),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json(
                [
                    'message' => "cURL Error #:" . $err
                ]
            )->setStatusCode(500);
        } else {
            return ['data' => json_decode($response, true)];
        }
    }

}

