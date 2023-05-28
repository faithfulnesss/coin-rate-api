<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Notifications\Notifiable;


/**
 * Class Subscription
 * 
 * Represents a subscription model and provides methods
 * to load and save collections of Subscription objects.
 * 
 */
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
    
    /**
     * Static method to load the subscriptions from the storage file
     *
     * @return Collection|Subscription[]
     */
    public static function loadSubscriptions(): Collection
    {
        // Read the contents of the subscriptions.json file using Storage facade
        $contents = Storage::get('subscriptions.json');

        // Decode the JSON contents into a collection 
        // or use an empty array if decoding fails
        $subscriptions = collect(@json_decode($contents, true) ?? []);

        // Convert each array item to a Subscription object
        $subscriptions = $subscriptions->map(function ($item) {
            return new self($item);
        });

        // Return collection of Subscription model object
        return $subscriptions;
    }

    /**
     * Static method to save the collection of Subscriptions in the storage file
     *
     * @param  Collection|Subscription[] $subscriptions
     * @return bool
     */
    public static function saveSubscriptions(Collection $subscriptions): bool
    {
        // Save the collection of Subscription model objects as a JSON
        // in the subscription.json file
        return Storage::put('subscriptions.json', $subscriptions->toJson());
    }    
}
