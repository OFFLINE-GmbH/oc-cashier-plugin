<?php namespace OFFLINE\Cashier\Components;

use Auth;
use Cms\Classes\ComponentBase;
use OFFLINE\Cashier\Models\User;
use Session;

class InvoicesList extends ComponentBase
{
    /**
     * The user the invoices belong to.
     *
     * @var User
     */
    public $user;
    /**
     * A list of all the invoices.
     *
     * @var array
     */
    public $invoices;

    public function componentDetails()
    {
        return [
            'name'        => 'offline.cashier::lang.components.invoicesList.name',
            'description' => 'offline.cashier::lang.components.invoicesList.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'userId'         => [
                'type'        => 'dropdown',
                'title'       => 'offline.cashier::lang.components.invoicesList.properties.userId.title',
                'description' => 'offline.cashier::lang.components.invoicesList.properties.userId.description',
            ],
            'includePending' => [
                'type'        => 'checkbox',
                'default'     => false,
                'title'       => 'offline.cashier::lang.components.invoicesList.properties.includePending.title',
                'description' => 'offline.cashier::lang.components.invoicesList.properties.includePending.description',
            ],
            'loadingText' => [
                'type'        => 'text',
                'default'     => 'Loading invoices...',
                'title'       => 'offline.cashier::lang.components.invoicesList.properties.loadingText.title',
                'description' => 'offline.cashier::lang.components.invoicesList.properties.loadingText.description',
            ],
        ];
    }

    public function getUserIdOptions()
    {
        $blank = ['' => trans('offline.cashier::lang.components.invoicesList.useSession')];

        return $blank + User::get()->pluck('username', 'id')->toArray();
    }

    public function onRun()
    {
        $this->getUser();
        $this->loadingText = $this->page['loadingText'] = $this->property('loadingText');
    }

    public function onFetchInvoices()
    {
        $this->invoices = $this->page['invoices'] = $this->getInvoices();
    }

    public function onDownloadInvoice()
    {
        $user = $this->getUser();

        // Since October does not support file downloads via the Ajax framework we
        // encrypt both the user and invoice id and redirect to the
        // "real" download route. The ids are encrypted to prevent tinkering
        // and attempts to download another user's invoices.
        $userId    = encrypt($user->id);
        $invoiceId = encrypt(post('id'));
        $route     = sprintf('/cashier/invoice/%s/%s', $userId, $invoiceId);

        /** @see routes.php */
        return redirect($route);
    }

    protected function getUser()
    {
        $user = $this->property('userId')
            ? User::whereId($this->property('userId'))->firstOrFail()
            : Auth::getUser();

        $this->user = $this->page['user'] = $user;

        return $user;
    }

    protected function getInvoices()
    {
        if ( ! $this->user) {
            $this->getUser();
        }

        if ($this->property('includePending')) {
            return $this->user->invoicesIncludingPending();
        }

        return $this->user->invoices();
    }
}
