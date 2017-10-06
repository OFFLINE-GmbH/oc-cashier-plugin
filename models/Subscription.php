<?php

namespace OFFLINE\Cashier\Models;


use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    public $table = 'offline_cashier_subscriptions';
}