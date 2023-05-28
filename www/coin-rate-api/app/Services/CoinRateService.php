<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

/**
 * Class CoinRateService
 *
 * Service class for retrieving coin rate from a third-party API (in this case CoinMarketCap API).
 */
class CoinRateService
{
    protected $apiKey;
    protected $apiUri;
    
    /**
     * Construct a new CoinRateService instance that contains API key and API URI.
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiKey = Config::get('services.cmp.token');
        $this->apiUri = 'https://pro-api.coinmarketcap.com/v1/';
    }
    
    /**
     * Get the current coin rate.
     *
     * @return float|null The current coin rate, or null if it cannot be retrieved.
     */
    public function getRate()
    {
        // Send a GET request to the third-party API endpoint with the required auth header 
        // and parameters for BTC-UAH rate
        $response = Http::withHeaders([
            'X-CMC_PRO_API_KEY' => $this->apiKey,
        ])->get($this->apiUri . 'cryptocurrency/quotes/latest', [
            'symbol' => 'BTC',
            'convert' => 'UAH',
        ]);
    
        // Extract the response body and decode it from JSON
        $data = @json_decode($response->getBody()->getContents(), true);
        
        // Access the necessary data in the response to retrieve the coin rate
        // If the @json_decode fails or the necessary data is not available 
        // set the rate to null
        $rate = $data['data']['BTC']['quote']['UAH']['price'] ?? null;

        return $rate;
    }
}
