<?php namespace OFFLINE\Cashier\Classes;


use Event;
use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends CashierWebhookController
{
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        logger()->debug('Received Stripe Webhook', [$payload]);

        Event::fire('offline.cashier::stripe.webhook.received', [$payload, $request]);
        Event::fire('offline.cashier::stripe.webhook.' . $payload['type'], [$payload, $request]);

        return parent::handleWebhook($request);
    }

    /**
     * Get the billable entity instance by Stripe ID.
     *
     * @param  string  $stripeId
     * @return \Laravel\Cashier\Billable
     */
    protected function getUserByStripeId($stripeId)
    {
        $model = getenv('STRIPE_MODEL') ?: config('services.stripe.model');

        return (new $model)->where('offline_cashier_stripe_id', $stripeId)->first();
    }
}
