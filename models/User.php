<?php

namespace OFFLINE\Cashier\Models;

use Exception;
use Illuminate\Support\Collection;
use OFFLINE\Cashier\Classes\Billable;
use RainLab\User\Models\User as UserBase;
use Stripe\Invoice as StripeInvoice;

class User extends UserBase
{
    use Billable;

    public $hasMany = [
        'subscriptions' => [
            Subscription::class,
            'order' => 'created_at desc',
        ],
    ];

    public static function fromSubscriptionId($subscriptionId)
    {
        $subscription = Subscription::where('stripe_id', $subscriptionId)
                                    ->firstOrFail(['user_id']);

        return static::with('subscriptions')->findOrFail($subscription->user_id);
    }

    public function subscriptions()
    {
        return $this->hasMany(
            Subscription::class,
            $this->getForeignKey()
        )->orderBy('created_at', 'desc');
    }

    /**
     * Find an invoice by ID. Overwrites original
     * method to return our custom Invoice model.
     *
     * @param  string $id
     *
     * @return \Laravel\Cashier\Invoice|null
     */
    public function findInvoice($id)
    {
        try {
            return new Invoice($this, StripeInvoice::retrieve($id, $this->getStripeKey()));
        } catch (Exception $e) {
            //
        }
    }

    /**
     * Get a collection of the entity's invoices.
     *
     * @param  bool  $includePending
     * @param  array $parameters
     *
     * @return \Illuminate\Support\Collection
     */
    public function invoices($includePending = false, $parameters = [])
    {
        $invoices = [];

        $parameters = array_merge(['limit' => 24], $parameters);

        $stripeInvoices = $this->asStripeCustomer()->invoices($parameters);

        // Here we will loop through the Stripe invoices and create our own custom Invoice
        // instances that have more helper methods and are generally more convenient to
        // work with than the plain Stripe objects are. Then, we'll return the array.
        if ( ! is_null($stripeInvoices)) {
            foreach ($stripeInvoices->data as $invoice) {
                if ($invoice->paid || $includePending) {
                    $invoices[] = new Invoice($this, $invoice);
                }
            }
        }

        return new Collection($invoices);
    }
}