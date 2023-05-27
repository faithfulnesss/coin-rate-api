<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Notifications\Notifiable;


class Subscription extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['email', 'subscription_date'];

    /**
     * Get the email address where notification should be sent.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public static function loadSubscriptions(): Collection
    {
        $contents = Storage::get('subscriptions.json');
        $subscriptions = collect(@json_decode($contents, true) ?? []);

        // Convert each array item to a Subscription object
        $subscriptions = $subscriptions->map(function ($item) {
            return new self($item);
        });

        return $subscriptions;
    }

    public static function saveSubscriptions(Collection $subscriptions): bool
    {
        // $subscriptions = self::getAllSubscriptions();
        
        // if ($subscriptions->firstWhere('email', $this->email)){
        //     return false;
        // }  
    
        // $subscriptions->push($this);
    
        return Storage::put('subscriptions.json', $subscriptions->toJson());
    
        // return true;
    }    
}
