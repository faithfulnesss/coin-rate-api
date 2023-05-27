<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;

use Symfony\Component\HttpFoundation\Response;

use App\Services\CoinRateService;

class CoinRateController extends Controller
{
    //
    private $coinRateService;

    public function __construct(CoinRateService $coinRateService)
    {
        $this->coinRateService = $coinRateService;
    }
    
    public function getRate()
    {
        $rate = $this->coinRateService->getRate();
        
        if(empty($rate)) {
            return response()->json(['msg' => 'Failed to fetch the BTC-UAH rate.'], Response::HTTP_BAD_REQUEST);            
        }

        return $rate;
    }
}
