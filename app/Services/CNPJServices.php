<?php

namespace App\Services;

class CNPJServices extends DefaultServices
{

    public function show($request)
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://www.receitaws.com.br/v1/cnpj/" . preg_replace('/\D/', '', $request->get('cnpj')),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Type: application/json",
                "cache-control: no-cache"
            ],
        ]);

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

            $data = json_decode($response, true);

            if (json_last_error() == JSON_ERROR_NONE) {
                return ['data' => $data];
            }

            return response()->json(
                [
                    'message' => "Muitas consultas realizadas. Por favor tente novamente mais tarde"
                ]
            )->setStatusCode(500);

        }
    }

}

