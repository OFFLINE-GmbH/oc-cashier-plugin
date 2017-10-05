<?php

namespace OFFLINE\Cashier\Classes;


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
     * Handle calls to missing methods on the controller.
     *
     * @param  array $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function missingMethod($parameters = [])
    {
        return new Response;
    }
}
