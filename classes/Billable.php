<?php namespace OFFLINE\Cashier\Classes;

use Dompdf\Dompdf;
use October\Rain\Extension\ExtensionBase;
use Stripe\Customer as StripeCustomer;
use Stripe\Token as StripeToken;
use OFFLINE\Cashier\Models\Subscription;
use Symfony\Component\HttpFoundation\Response;
use System\Traits\ViewMaker;

class Billable extends ExtensionBase
{
    use \Laravel\Cashier\Billable;

    protected $parent;

    /** @var string[] Columns that need to be overridden for OC */
    protected $renamedColumns = [
        'stripe_id', 'card_brand', 'card_last_four', 'trial_ends_at'
    ];

    public function __construct($parent)
    {
        /** Used to redirect to the RainLab.User model */
        $this->parent = $parent;
        /** Define subscriptions relationship the way OC want */
        $this->parent->hasMany['subscriptions'] = [Subscription::class, 'order' => 'created_at desc'];
    }

    /** subscriptions is needed when method is called directly
     *      so the Cashier Billable method is never called
     * @return mixed
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class)->orderBy('created_at', 'desc');
    }

    /** Overridden to fix compatibility between the old Cashier version and the new Stripe API
     *      sources and subscriptions are not returned as default
     *      see https://stripe.com/docs/upgrades#2020-08-27
     * @return StripeCustomer
     */
    public function asStripeCustomer()
    {
        return StripeCustomer::retrieve([
            'id' => $this->stripe_id,
            'expand' => ['sources', 'subscriptions']
        ], $this->getStripeKey());
    }

    /** Overridden to fix compatibility between the old Cashier version and the new Stripe API
     * @param array $options
     * @return mixed
     */
    public function createAsStripeCustomer(array $options = [])
    {
        $options = array_key_exists('email', $options)
            ? $options
            : array_merge($options, ['email' => $this->email]);

        $customer = StripeCustomer::create(
            $options,
            $this->getStripeKey()
        );

        $this->stripe_id = $customer->id;

        $this->save();

        /**
         * FIX IS HERE: In cashier, the $customer object is directly returned, but it doesn't includes the subscriptions
         *      subscriptions is needed into SubscriptionBuilder in line 205
         *      so we fetch asStripeCustomer which include them
         */
        return $this->parent->asStripeCustomer();
    }

    /** Overridden to fix compatibility between the old Cashier version and the new Stripe API
     * @param $token
     * @return mixed
     */
    public function updateCard($token)
    {
        $customer = $this->asStripeCustomer();

        $token = StripeToken::retrieve($token, ['api_key' => $this->getStripeKey()]);

        if ($token[$token->type]->id === $customer->default_source) {
            return;
        }

        $card = $customer->sources->create(['source' => $token]);
        $customer->default_source = $card->id;

        $customer->save();

        /**
         * FIX IS HERE: In cashier, the $customer object is directly returned, but it doesn't includes the sources.
         *      Cashier was previously asking for sources($this->default_source)
         *      because default_source = just created card we can safely use the $card instance directly
         */
        $source = $customer->default_source
            ? $card
            : null;

        $this->fillCardDetails($source);

        $this->save();
    }

    /**
     * Below we use magic methods to retrieve the renamed column directly from the parent instance
     * So we don't need to override the Billable class to modify the columns name
     * We could have created accessors and mutators to do the same trick
     */
    public function __call($name, $arguments)
    {
        return $this->parent->$name(...$arguments);
    }

    public function __get($name)
    {
        if (in_array($name, $this->renamedColumns, true)) {
            return $this->parent->{'offline_cashier_'.$name};
        }

        return $this->parent->$name;
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->renamedColumns, true)) {
            return $this->parent->{'offline_cashier_'.$name} = $value;
        }

        return $this->parent->$name = $value;
    }

    public function __isset($name)
    {
        return isset($this->parent->$name);
    }
}
