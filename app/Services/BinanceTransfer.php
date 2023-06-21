<?php

namespace App\Services;

use GuzzleHttp\Client;

class BinanceTransfer
{
    protected $client;
    protected $apiKey;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = "1dV8SGNiGMgBKozCMLdiEcjXOYz9n6LDmtz56CJZZyRw3dwGV8EnULiJGJAXjnVd";
        $this->secretKey = "dZriYcnt8doYPvdQmkDDMvpaNi6mkDYgRD9NikqiTwT8UV0bP690vRN2XtahMTWv";
        $this->baseUrl = 'https://api.binance.com/binancepay/openapi/v2/order';
    }

    public function transferFunds(string $asset, float $amount, string $fromAccount, string $toAccount)
    {
        $timestamp = round(microtime(true) * 1000);
        $params = [
            'asset' => $asset,
            'amount' => $amount,
            // 'type' => 1,
            'createType' => 'QR_CODE',
            // 'orderId' => '323fdfyuuhghbdfgre',
            // 'fromAccountType' => $fromAccount,
            // 'toAccountType' => $toAccount,
            'timestamp' => $timestamp,
        ];

        $query = http_build_query($params);
        $signature = hash_hmac('sha256', $query, $this->secretKey);

        $response = $this->client->post($this->baseUrl, [
            'headers' => [
                'X-MBX-APIKEY' => $this->apiKey,
            ],
            'form_params' => $params,
            'query' => [
                'signature' => $signature,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
