<?php namespace OFFLINE\Cashier\Models;

use Model;
use October\Rain\Database\Traits\Validation;

class Settings extends Model
{
    use Validation;

    public $rules = [
        'currency_currency' => 'required',
        'currency_symbol' => 'required'
    ];

    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'offline_cashier_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
