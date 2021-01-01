<?php namespace OFFLINE\Cashier\Models;

use Laravel\Cashier\Cashier;

trait FormatsAmount
{
    /**
     * Format the given amount into a string based
     * on the Stripe model's preferences or the via
     * the event system specified format.
     *
     * @param  int $amount
     *
     * @return string
     */
    protected function formatAmount($amount)
    {
        $returns = \Event::fire('offline.cashier::format.amount', $amount);

        return count($returns) < 1
            ? Cashier::formatAmount($amount)
            : $returns[0];
    }
}
