<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Notifications\CoinRateNotification;
use App\Services\CoinRateService;

use Symfony\Component\HttpFoundation\Response;



/**
 * SubscriptionController
 */
class SubscriptionController extends Controller
{

    private $coinRateService;

    /**
     * SubscriptionController constructor.
     *
     * @param CoinRateService $coinRateService The CoinRateService instance 
     * from which we fetch current rate
     */
    public function __construct(CoinRateService $coinRateService)
    {
        $this->coinRateService = $coinRateService;
    }
    
    /**
     * Store a new subscription.
     *
     * @param Request $request The request object
     * @return JsonResponse The JSON response
     */
    public function store(Request $request)
    {    
        // Validate whether the email is valid
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        // Return a conflict response if email is not valid
        if ($validator->fails()){
            return response()->json(['msg' => 'Failed email validation',], Response::HTTP_CONFLICT);
        }

        // Load subscriptions from storage
        $subscriptions = Subscription::loadSubscriptions();

        // Get the email from the request
        $email = $request->input('email');

        // Check if the email is already present in the subscriptions.json
        if ($subscriptions->contains('email', $email)){
            return response()->json(['msg' => 'Email is already present',], Response::HTTP_CONFLICT);
        }  

        // Create a new Subscription instance and push it in the subscription.json
        $subscription = new Subscription(['email' => $email, 'subscription_date' => date("Y-m-d")]);
        
        Subscription::saveSubscriptions($subscriptions->push($subscription));

        return response()->json(['msg' => 'Email successfully created'], Response::HTTP_CREATED);
    }
    
    /**
     * Send emails to all subscribers.
     *
     * @return JsonResponse The JSON response
     */
    public function sendEmails() {
        // Get the current rate from the service
        $rate = $this->coinRateService->getRate();

        // Load the subscriptions from storage
        $subscriptions = Subscription::loadSubscriptions();
        
        // Send notifications to all emails that are present inside the subscriptions.json file
        // with the current coin rate using Notification facade    
        Notification::send($subscriptions, new CoinRateNotification($rate));

        return response()->json(['msg' => 'Emails have been successfully sent'], Response::HTTP_OK);
    }
}
