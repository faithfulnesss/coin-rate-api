<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Notifications\CoinRateNotification;

use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
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

        $subscription = Subscription::create(['email' => $email, 'subscription_date' => date("Y-m-d")]);

        if(!$subscription){
            return response()->json(['msg' => 'Email is already present',], Response::HTTP_CONFLICT);
        }
        
        return response()->json(['msg' => $subscription], Response::HTTP_CREATED);

    }

    public function sendEmails(CoinRateService $coinRateService) {
        // $rate = $coinRateService->getRate();

        $subscriptions = Subscription::getAllSubscriptions();
        
        Notification::send($subscriptions, new CoinRateNotification($rate));
    
    }
}
