<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class BinanceController extends Controller
{
    public function getAccountInfo()
    {
        $api_key = '1dV8SGNiGMgBKozCMLdiEcjXOYz9n6LDmtz56CJZZyRw3dwGV8EnULiJGJAXjnVd';
        $api_secret = 'dZriYcnt8doYPvdQmkDDMvpaNi6mkDYgRD9NikqiTwT8UV0bP690vRN2XtahMTWv';

        // API endpoint for account information
        $url = 'https://api.binance.com/api/v3/account';

        // Add timestamp parameter
        $params['timestamp'] = round(microtime(true) * 1000);

        // Create a signature using HMAC-SHA256
        $query_string = http_build_query($params, '', '&');
        $signature = hash_hmac('sha256', $query_string, $api_secret);
        $requestUrl = "{$url}?{$query_string}&signature={$signature}";
        print_r($requestUrl);
        // Add API Key and signature to the request headers
        $headers = ['X-MBX-APIKEY' => $api_key];

        try {
            $client = new Client();
            $response = $client->get($url, [
                'headers' => $headers,
                'query' => array_merge($params, ['signature' => $signature]),
            ]);

            $account_info = json_decode($response->getBody(), true);
            $balances = $account_info['balances'];
            $wallet_amounts = [];

            foreach ($balances as $balance) {
                $symbol = $balance['asset'];
                $free = $balance['free'];
                $locked = $balance['locked'];
                $estimated_balance = bcadd($free, $locked, 8); // Calculate estimated balance

                if ($estimated_balance != '0.00000000') {
                    $wallet_amounts[$symbol] = $estimated_balance;
                }
            }

            return response()->json([
                'status' => 200,
                'data' => $wallet_amounts
            ]);

            //return view('wallet-amount', ['wallet_amounts' => $wallet_amounts]);

            // $wallet_amount = $account_info['balances'][0]['free'];
            // return "Your Binance wallet amount: $wallet_amount";
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $error_message = $e->getResponse()->getBody();
            } else {
                $error_message = $e->getMessage();
            }
            return "Error: $error_message";
        }
    }

    public function generatePaymentLink(Request $request)
    {
        $coin = 'BNB'; // Replace 'BNB' with the desired cryptocurrency symbol
        $amount = 10; // Replace with the desired payment amount

        $apiKey = '1dV8SGNiGMgBKozCMLdiEcjXOYz9n6LDmtz56CJZZyRw3dwGV8EnULiJGJAXjnVd';
        $apiSecret = 'dZriYcnt8doYPvdQmkDDMvpaNi6mkDYgRD9NikqiTwT8UV0bP690vRN2XtahMTWv';

        $timestamp = time();

        $query = http_build_query([
            'coin' => $coin,
            'fiat' => 'USD', // Replace with the desired fiat currency
            'amount' => $amount,
            'side' => 'BUY', // 'BUY' or 'SELL'
            'type' => 'LIMIT', // 'LIMIT' or 'MARKET'
            'timestamp' => $timestamp
        ]);

        $signature = hash_hmac('sha256', $query, $apiSecret);

        $client = new Client();

        $response = $client->post('https://api.binance.com/api/v3/p2p/order', [
            'headers' => [
                'X-MBX-APIKEY' => $apiKey,
            ],
            'query' => $query,
            'form_params' => [
                'signature' => $signature,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['url'])) {
            $paymentLink = $data['url'];

            // Return the payment link to your view or as an API response
            return view('payment')->with('paymentLink', $paymentLink);
        } else {
            // Handle API error
            return response()->json(['error' => 'Failed to generate payment link.']);
        }
    }
}
