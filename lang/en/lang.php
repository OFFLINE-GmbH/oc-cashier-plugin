<?php return [
    'plugin'     => [
        'name'                       => 'Cashier',
        'description'                => 'Stripe Payments for October CMS using Laravel Cashier',
        'manage_settings'            => 'Manage Cashier settings.',
        'manage_settings_permission' => 'Can manage Cashier settings',
    ],
    'components' => [
        'stripeElementsForm' => [
            'name'        => 'Stripe Elements Form',
            'description' => 'Securely collect sensitive card details using Stripe Elements',
            'properties'  => [
                'includeStripeJs'   => [
                    'title'       => 'Include Stripe JS',
                    'description' => 'Include all required Stripe JS file from within this component',
                ],
                'includeCss'        => [
                    'title'       => 'Include CSS',
                    'description' => 'Include the styles partial from the component with some default Stripe styles',
                ],
                'submitButtonLabel' => [
                    'title'       => 'Submit button label',
                    'description' => 'The submit button label during submit',
                ],
            ],
        ],
        'needsSubscription'  => [
            'name'        => 'Subscription needed',
            'description' => 'Redirect users that don\'t have a valid subscription',
            'properties'  => [
                'redirect'     => [
                    'title'       => 'Redirect',
                    'description' => 'Where to redirect unauthorized users to',
                ],
                'subscription' => [
                    'title'       => 'Subscription',
                    'description' => 'The name of the needed subscription',
                ],
            ],
        ],
        'invoicesList'       => [
            'name'        => 'Invoices list',
            'description' => 'Lists all invoices for a user',
            'useSession'  => 'Use logged in user',
            'properties'  => [
                'userId'         => [
                    'title'       => 'User ID',
                    'description' => 'Show invoices for this user. Leave blank to use logged in user.',
                ],
                'includePending' => [
                    'title'       => 'Include pending',
                    'description' => 'Include pending invoices in the list',
                ],
                'loadingText'    => [
                    'title'       => 'Loading text',
                    'description' => 'This text is displayed while the invoices are fetched from the payment API',
                ],
            ],
        ],
    ],
    'settings'   => [
        'invoice_vendor'            => 'Vendor name',
        'invoice_vendor_comment'    => 'This name will be printed on the generated PDF invoices.',
        'invoice_product'           => 'Product name',
        'invoice_product_comment'   => 'This name will be printed on the generated PDF invoices.',
        'invoice_view'              => 'Invoice view file',
        'invoice_view_comment'      => 'Specify a custom view path to use your own invoice view file.',
        'invoice_data'              => 'Additional invoice data (Phone numbers, contact information, etc.)',
        'invoice_data_comment'      => 'These values will be passed to your invoice view.',
        'invoice_data_key'          => 'Variable name',
        'invoice_data_value'        => 'Variable value',
        'currency_currency'         => 'Currency',
        'currency_currency_comment' => 'Default to this currency for all Cashier operations.',
        'currency_symbol'           => 'Currency symbol',
        'currency_symbol_comment'   => 'The symbol is guessed based on the above "Currency" setting. If the symbol cannot be guessed an exception will be thrown. In this case enter your desired symbol here manually.',
        'sections'                  => [
            'invoice'          => 'PDF invoices',
            'invoice_comment'  => 'Configure the appearance of the generated PDF invoices',
            'currency'         => 'Currency',
            'currency_comment' => 'Configure what currency Cashier should use by default',
        ],
    ],
];