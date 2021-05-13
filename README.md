# October Cashier Plugin

October CMS plugin to handle Stripe and Braintree payments using [Laravel Cashier](https://laravel.com/docs/5.5/billing).

This plugin integrates Laravel Cashier with the help of [Rainlab.User](https://github.com/rainlab/user-plugin) into October CMS. You can do "one-off" charges on your Rainlab users or subscribe them to your Stripe plans.

The plugin comes preloaded with simple Stripe Elements form and invoice list components. All features of Laravel Cashier are available. An easily customizable invoice view and some useful events have been added for this October CMS version of Cashier.

## Breaking changes in v2.0.0

In version 2.0.0 of this plugin Laravel Cashier was updated to version 9.0. This makes the plugin
compatible with Laravel 5 and Laravel 6 based October versions.

With this update, some architectural problems with the plugins have been solved that might introduce
breaking changes depending on your setup:

* The `\OFFLINE\Cashier\Models\User` was removed completely. Use `\RainLab\User\Models\User` in its place.
* Because the custom User model was removed, you have to make sure any relations that might point to the old
  model class are moved to the RainLab.User model.
* The `stripeelementsform/script.js` partial was renamed to `script.htm`. If you override this partial
  make sure to rename your overriding partial as well.

## Installation

In `config/services.php` replace the `stripe` configuration with the following:
 
 ```php
'stripe' => [
    'model'   => \RainLab\User\Models\User::class,
    'key'     => env('STRIPE_KEY'),
    'secret'  => env('STRIPE_SECRET'),
    'webhook' => [
        'url'     => '/stripe/webhook',
        'handler' => '\OFFLINE\Cashier\Classes\WebhookController@handleWebhook'
    ]
],
```

Now add your `STRIPE_KEY` and `STRIPE_SECRET` to your `.env` file.

```
STRIPE_KEY=pk_test_XXXXXXXXXXXXXXXXXXXXXX
STRIPE_SECRET=sk_test_XXXXXXXXXXXXXXXXXXXXXX
```

Make sure to include the [October CMS framework extras](http://octobercms.com/docs/ajax/extras) if you want to use the `StripeElementsForm` component.

You're done!

## Handle Stripe Webhooks

To receive Stripe webhooks configure Stripe to send them to `https://<your-site>/stripe/webhook` (you can change this URL via the `services.stripe.webhook.url` config entry).
  
Subscription cancellation on failed charges are handled by default by Laravel Cashier. If you wish to react to other webhooks [listen for the generic `offline.cashier::stripe.webhook.received` event](http://octobercms.com/docs/services/events#events-subscribing) that is fired for all incomming webhooks or create a listener for a specific webhook event like `offline.cashier::stripe.webhook.invoice.payment_succeeded` (using the format `offline.cashier::stripe.webhook.<stripe_webhook_type>`). All event listeners receive a `$payload` and a `$request` variable.

Alternatively you can set your own webhook handler controller in the `services.stripe.webhook.handler` config entry and implement everything yourself.

```php
Event::listen('offline.cashier::stripe.webhook.invoice.payment_succeeded', function ($payload, $request) {
    $subscription = $payload['data']['object']['subscription'];
    
    $user = \RainLab\User\Models\User::fromSubscriptionId($subscription);

    $user->payment_succeeded_at = \Carbon\Carbon::now();
    $user->save();

    logger()->debug('Received payment', compact('user', 'subscription'));
});
```

## Components

### InvoicesList

This component lists all invoices of a specific user account.


#### Use your own number formatter

If you want to have complete control over the way the amounts are formatted in your invoices, you have the possibility to listen for the `offline.cashier::format.amount` event and format the amount yourself.

```php
Event::listen('offline.cashier::format.amount', function ($amount) {
    return number_format((float)$amount / 100, 2, '.', "'") . ' CHF';
});
```

#### Customize the invoice template

The invoice template is stored in `views/receipt.htm`. You can copy this template and store it in your own plugin or theme. To load your own partial for the invoices set the new view path in the backend settings of Cashier. 

You can pass additional data to the invoice using the repeater on the backend settings form. Each variable you specify will be available in the template. This is useful if you want to show a phone number or email address on your invoices. 

#### Properties

##### userId

Specify the id of the user you want to load the invoices for. If you leave this blank the currently logged in user will be used.

##### includePending

Whether or not to include pending invoices in the list.

##### loadingText

For performance reasons the invoice list is loaded asynchronously. During the loading period this string is displayed to the user (defaults to `Loading invoices...`).


### StripeElementsForm

This component provides you with a simple starting point to integrate [Stripe Elements](https://stripe.com/docs/elements) into your Website.

Simply add the component **to your page** and you will be presented with a simple credit card input form.

#### Token Handler Callback

To extend the default `stripeTokenHandler` function that receives the generated Stripe token, copy the `plugins/offline/cashier/components/stripeelementsform/tokenHandler.htm` to `themes/<yourtheme>/partials/stripeElementsForms/tokenHandler.htm` and modify as needed.

#### Handle form submission

When the user submits the form the `offline.cashier::stripeElementForm.submit` event is triggered. You can [listen for this event in your own plugin](http://octobercms.com/docs/services/events#events-subscribing) and store the payment information for later use, charge your user or create a subscription.

Checkout the [Laravel Cashier Documentation](https://laravel.com/docs/5.5/billing) for more information on how to use the generated `stripeToken` to charge or subscribe a user. 

For a working example see the code below:

```php
public function boot()
{
    parent::boot();

    Event::listen('offline.cashier::stripeElementForm.submit', function ($post) {
        $token = $post['token']['id'] ?? null;
        if ( ! $token) {
            throw new \RuntimeException('Stripe token is missing!');
        }

        $user = \Auth::getUser();
        $user->newSubscription('main', 'users-selected-plan-id')->create($token);

        return [
            'redirect' => \Url::to('thank-you')
        ];
    });
}
```

#### Properties

##### includeStripeJs

This property includes the `https://js.stripe.com/v3/` code into your page. If you have multiple instances of the component on one page make sure to enable this property only on one of them.
If you include the code globally you can safely uncheck this property.

##### includeCss

This property includes the `styles.htm` partial from the component (which you can overwrite) with the component. If you defined your Stripe Elements styles gl

##### submitButtonLabel

This property specifies the submit button's label during the form submission. 


### NeedsSubscription

Place this component on any page that is only accessible by users with a valid subscription. Users without a valid subscription are redirected to the page you defined.

#### Extend the subscription logic

If you need to extend the subscription logic you can listen for the `offline.cashier::subscription.check` event. The event receives the `user` and the `NeedsSubscription` component instance.

If you return `true` from any event listener the user will be allowed to view the page. If you return `false` from any event listener the user will be redirected. Please be aware that any `true` value takes precedence. If you don't return any `boolean` value the check will fallback to Laravel Cashier's `subscribed` method.


##### Extend the subscription logic

If you need to extend the subscription logic you can listen for the `offline.cashier::subscription.check` event. The event receives the `user` and the `NeedsSubscription` component instance.

If you return `true` from any event listener the user will be allowed to view the page. If you return `false` from any event listener the user will be redirected. Please be aware that any `true` value takes precedence. If you don't return any `boolean` value the check will fallback to Laravel Cashier's `subscribed` method.

```php
 Event::listen('offline.cashier::subscription.check', function ($user, $component) {
    if ( ! $user) {
        return false;
    }

    if (isBanned($user)) {
        return false;
    }

    return $user->subscribed($component->property('subscription'));
});
```

#### Properties

##### redirect

Unauthorized users are redirected to this route.

##### subscription

The name of the subscription a user needs to access the page. Default is `main`.
