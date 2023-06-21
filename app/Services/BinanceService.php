<?php

namespace App\Services;

use GuzzleHttp\Client;

class BinanceService
{
    protected $baseUrl = 'https://api.binance.com';

    protected $apiKey;
    protected $secretKey;

    public function __construct()
    {
        $this->apiKey = "1dV8SGNiGMgBKozCMLdiEcjXOYz9n6LDmtz56CJZZyRw3dwGV8EnULiJGJAXjnVd";
        $this->secretKey = "dZriYcnt8doYPvdQmkDDMvpaNi6mkDYgRD9NikqiTwT8UV0bP690vRN2XtahMTWv";
    }

    public function createOrder($symbol, $side, $quantity, $price)
    {
        $client = new Client([
            'base_uri' => $this->baseUrl,
        ]);

        $timestamp = round(microtime(true) * 1000);
        $params = [
            'symbol' => $symbol,
            'side' => $side,
            'type' => 'LIMIT',
            'quantity' => $quantity,
            'price' => $price,
            'timeInForce' => 'GTC',
            'timestamp' => $timestamp,
        ];

        $query = http_build_query($params);
        $signature = hash_hmac('sha256', $query, $this->secretKey);

        $response = $client->post('/api/v3/order', [
            'headers' => [
                'X-MBX-APIKEY' => $this->apiKey,
            ],
            'form_params' => $params,
            'query' => $query.'&signature='.$signature,
        ]);

        return $response->getBody()->getContents();
    }
}
