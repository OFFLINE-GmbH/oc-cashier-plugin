<?php

namespace OFFLINE\Cashier\Models;

use Laravel\Cashier\Invoice as CashierInvoice;
use View;

class Invoice extends CashierInvoice
{
    use FormatsAmount;

    /**
     * Get the View instance for the invoice.
     *
     * @param  array $data
     *
     * @return \Illuminate\View\View
     */
    public function view(array $data = [])
    {
        $settingsData = Settings::get('invoice_data', []);
        $settingsData = collect($settingsData)->mapWithKeys(function ($item) {
            return [$item['key'] => $item['value']];
        })->toArray();

        $view = Settings::get('invoice_view') ?: 'offline.cashier::receipt';

        $this->formatAmount(200);

        return View::make($view, array_merge($data, $settingsData, [
            'invoice' => $this,
            'owner'   => $this->owner,
            'user'    => $this->owner,
        ]));
    }

    /**
     * Get all of the invoice items by a given type.
     *
     * @param  string $type
     *
     * @return array
     */
    public function invoiceItemsByType($type)
    {
        $lineItems = [];

        if (isset($this->lines->data)) {
            foreach ($this->lines->data as $line) {
                if ($line->type == $type) {
                    $lineItems[] = new InvoiceItem($this->owner, $line);
                }
            }
        }

        return $lineItems;
    }
}