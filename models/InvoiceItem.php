<?php

namespace OFFLINE\Cashier\Models;


use Laravel\Cashier\InvoiceItem as CashierInvoiceItem;

class InvoiceItem extends CashierInvoiceItem
{
    use FormatsAmount;
}