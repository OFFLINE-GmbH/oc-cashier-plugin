<?php namespace OFFLINE\Cashier\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'offline_cashier_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}