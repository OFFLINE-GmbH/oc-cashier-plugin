<?php return [
    'plugin'     => [
        'name'                       => 'Cashier',
        'description'                => 'Stripe-Zahlungen für October CMS mit Laravel Cashier',
        'manage_settings'            => 'Cashier-Einstellungen verwalten',
        'manage_settings_permission' => 'Kann Cashier-Einstellungen verwalten',
    ],
    'components' => [
        'stripeElementsForm' => [
            'name'        => 'Stripe Elements Formular',
            'description' => 'Sammelt Kreditkarten-Daten mit Stripe Elements',
            'properties'  => [
                'includeStripeJs'   => [
                    'title'       => 'Stripe JS einbinden',
                    'description' => 'Alle benötigten Stripe JS files mit der Komponente einbinden',
                ],
                'includeCss'        => [
                    'title'       => 'CSS einbinden',
                    'description' => 'Das Styles-Partial mit Stripe Standard Styles einbinden',
                ],
                'submitButtonLabel' => [
                    'title'       => 'Submit-Button Label',
                    'description' => 'Das Label für den Submit-Button während der Verarbeitung',
                ],
            ],
        ],
        'needsSubscription'  => [
            'name'        => 'Subscription benötigt',
            'description' => 'Leitet Benuzter ohne gültige Subscription um',
            'properties'  => [
                'redirect'     => [
                    'title'       => 'Umleitungsziel',
                    'description' => 'Hier hin werden unberechtigte User umgeleitet',
                ],
                'subscription' => [
                    'title'       => 'Subscription',
                    'description' => 'Name der benötigten Subscription',
                ],
            ],
        ],
        'invoicesList'       => [
            'name'        => 'Rechnungsliste',
            'description' => 'Liste aller Rechnungen eines Benutzers',
            'useSession'  => 'Benutze eingeloggten User',
            'properties'  => [
                'userId'         => [
                    'title'       => 'User-ID',
                    'description' => 'Zeige die Rechnungen für diesen Benutzer. Leer lassen um den eingeloggten Benutzer zu verwenden',
                ],
                'includePending' => [
                    'title'       => 'Pendente Rechnungen anzeigen',
                    'description' => 'Zeige auch pendente Rechnungen in der Liste an',
                ],
                'loadingText'    => [
                    'title'       => 'Lade-Text',
                    'description' => 'Dieser Text wird angezeigt während dem die Rechnungen von der Zahlungs-API geladen werden',
                ],
            ],
        ],
    ],
    'settings'   => [
        'invoice_vendor'            => 'Name des Betreibers',
        'invoice_vendor_comment'    => 'Dieser Name erscheint auf der Rechnung.',
        'invoice_product'           => 'Produktname',
        'invoice_product_comment'   => 'Dieser Name erscheint auf der Rechnung.',
        'invoice_view'              => 'View-Datei für die Rechnungsvorlage',
        'invoice_view_comment'      => 'Gib einen Pfad zu deiner eigenen View an um die Standardvorlage zu überschreiben.',
        'invoice_data'              => 'Zusätzliche Rechnungsdaten (Telefonnummer, Kontaktinfo, etc.)',
        'invoice_data_comment'      => 'Alle Werte werden als Variablen deiner Rechnungsvorlagen-View übergeben.',
        'invoice_data_key'          => 'Variablen-Name',
        'invoice_data_value'        => 'Variablen-Wert',
        'currency_currency'         => 'Währung',
        'currency_currency_comment' => 'Definiere welche Währung standardmässig für alle Cashier Aktionen verwendet werden soll.',
        'currency_symbol'           => 'Währungssymbol',
        'currency_symbol_comment'   => 'Es wird versucht das Symbol anhang der Währung zu erraten. Schlägt dies fehl wird eine Exception generiert. In diesem Fall muss du dein gewünschtes Symbol hier manuell eintragen.',
        'sections'                  => [
            'invoice'          => 'PDF-Rechnungen',
            'invoice_comment'  => 'Konfiguriere das Aussehen der PDF-Rechnungen',
            'currency'         => 'Währung',
            'currency_comment' => 'Konfiguriere die Währung die Cashier verwenden soll',
        ],
    ],
];