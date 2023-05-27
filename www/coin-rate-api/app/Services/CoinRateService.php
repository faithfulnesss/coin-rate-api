<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class CoinRateService
{
    protected $apiKey;
    protected $apiUri;

    public function __construct()
    {

        $this->apiKey = Config::get('services.cmp.token');
        $this->apiUri = 'https://pro-api.coinmarketcap.com/v1/';
    
    }

    public function getRate()
    {
        $response = Http::withHeaders([
            'X-CMC_PRO_API_KEY' => $this->apiKey,
        ])->get($this->apiUri . 'cryptocurrency/quotes/latest', [
            'symbol' => 'BTC',
            'convert' => 'UAH',
        ]);
    
        $data = @json_decode($response->getBody()->getContents(), true);
        
        $rate = $data['data']['BTC']['quote']['UAH']['price'] ?? null;

        return $rate;
    }
}
