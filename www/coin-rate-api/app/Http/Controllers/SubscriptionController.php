<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Notifications\CoinRateNotification;
use App\Services\CoinRateService;

use Symfony\Component\HttpFoundation\Response;



class SubscriptionController extends Controller
{

    private $coinRateService;

    public function __construct(CoinRateService $coinRateService)
    {
        $this->coinRateService = $coinRateService;
    }

    //
    public function store(Request $request)
    {    
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()){
            return response()->json(['msg' => 'Failed email validation',], Response::HTTP_CONFLICT);
        }

        $email = $request->input('email');

        $subscription = new Subscription(['email' => $email, 'subscription_date' => date("Y-m-d")]);

        $subscription_created = $subscription->save();

        if(!$subscription_created){
            return response()->json(['msg' => 'Email is already present',], Response::HTTP_CONFLICT);
        }
        
        return response()->json(['msg' => 'Email successfully created'], Response::HTTP_CREATED);
    }

    public function sendEmails() {
        $rate = $this->coinRateService->getRate();

        $subscriptions = Subscription::getAllSubscriptions();
        
        Notification::send($subscriptions, new CoinRateNotification($rate));

        return response()->json(['msg' => 'Emails successfully have been sent', Response::HTTP_OK]);
    }
}
