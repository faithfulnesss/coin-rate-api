<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use App\Services\CoinRateService;

    /**
     * Class CoinRateController
     */
class CoinRateController extends Controller
{
    private $coinRateService;
    
    /**
     * CoinRateController constructor.
     *
     * @param CoinRateService $coinRateService parameter is typehinted with the CoinRateService class
     */

    public function __construct(CoinRateService $coinRateService)
    {
        $this->coinRateService = $coinRateService;
    }
        
    /**
     * Get the current BTC-UAH rate.
     *
     * @return JsonResponse The JSON response with the rate from the CoinRateService
     */
    public function getRate()
    {
        // Call the CoinRateService to retrieve the current BTC-UAH rate
        $rate = $this->coinRateService->getRate();

        // If the rate is empty or unavailable, return an error response
        if(empty($rate)) {
            return response()->json(['msg' => 'Failed to fetch the BTC-UAH rate.'], Response::HTTP_BAD_REQUEST);            
        }

        // Return a successful response with the rate
        return response()->json(['rate' => $rate], Response::HTTP_OK);
    }
}
