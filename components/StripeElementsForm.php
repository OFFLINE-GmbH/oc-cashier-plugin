<?php namespace OFFLINE\Cashier\Components;

use Cms\Classes\ComponentBase;
use Event;

class StripeElementsForm extends ComponentBase
{
    /**
     * Whether or not include the stripe JS files.
     *
     * @var boolean
     */
    public $includeStripeJs = true;
    /**
     * Whether or not include the default styles partial.
     *
     * @var boolean
     */
    public $includeCss = true;
    /**
     * The Stripe Publishable Key
     *
     * @var string
     */
    public $stripeKey;
    /**
     * Button label during submit
     *
     * @var string
     */
    public $submitButtonLabel = '...';

    public function componentDetails()
    {
        return [
            'name'        => 'offline.cashier::lang.components.stripeElementsForm.name',
            'description' => 'offline.cashier::lang.components.stripeElementsForm.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'includeStripeJs'   => [
                'type'        => 'checkbox',
                'title'       => 'offline.cashier::lang.components.stripeElementsForm.properties.includeStripeJs.title',
                'description' => 'offline.cashier::lang.components.stripeElementsForm.properties.includeStripeJs.description',
                'default'     => true,
            ],
            'includeCss'        => [
                'type'        => 'checkbox',
                'title'       => 'offline.cashier::lang.components.stripeElementsForm.properties.includeCss.title',
                'description' => 'offline.cashier::lang.components.stripeElementsForm.properties.includeCss.description',
                'default'     => true,
            ],
            'submitButtonLabel' => [
                'type'        => 'string',
                'title'       => 'offline.cashier::lang.components.stripeElementsForm.properties.submitButtonLabel.title',
                'description' => 'offline.cashier::lang.components.stripeElementsForm.properties.submitButtonLabel.description',
                'default'     => '...',
            ],
        ];
    }

    public function onRender()
    {
        $this->includeStripeJs   = $this->page['includeStripeJs'] = $this->property('includeStripeJs');
        $this->includeCss        = $this->page['includeCss'] = $this->property('includeCss');
        $this->submitButtonLabel = $this->page['submitButtonLabel'] = $this->property('submitButtonLabel');
        $this->stripeKey         = $this->page['stripeKey'] = config('services.stripe.key');
    }

    public function onSubmit()
    {
        return Event::fire('offline.cashier::stripeElementForm.submit', [post(), $this]);
    }
}
