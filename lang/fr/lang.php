<?php return [
    'plugin'     => [
        'name'                       => 'Cashier',
        'description'                => 'Encaissez vos paiement avec Stripe sur OctoberCMS à l\'aide de Laravel Cashier',
        'manage_settings'            => 'Gestion de Cashier.',
        'manage_settings_permission' => 'Peut modifier les paramètres de Cashier',
    ],
    'components' => [
        'stripeElementsForm' => [
            'name'        => 'Elements de formulaire Stripe',
            'description' => 'Collectez de façon sécurisé les informations de paiements avec les éléments de formulaire Stripe',
            'properties'  => [
                'includeStripeJs'   => [
                    'title'       => 'Intégrer Stripe JS',
                    'description' => 'Intègre tout les fichiers JS de Stripe nécessaires dans ce composant',
                ],
                'includeCss'        => [
                    'title'       => 'Intégrer le CSS',
                    'description' => 'Intègre le fichiers de styles CSS comprenant certains paramètre par défaut de Stripe',
                ],
                'submitButtonLabel' => [
                    'title'       => 'Texte du bouton de paiement',
                    'description' => 'Le texte inscrit sur le bouton de paiement durant l\'envoi',
                ],
            ],
        ],
        'needsSubscription'  => [
            'name'        => 'Abonnement nécessaire',
            'description' => 'Redirige les utilisateurs qui n\'ont pas d\'abonnement en cours.',
            'properties'  => [
                'redirect'     => [
                    'title'       => 'Redirection',
                    'description' => 'Où rediriger les utilisateurs n\'ayant pas l\'accès',
                ],
                'subscription' => [
                    'title'       => 'Abonnement',
                    'description' => 'Le nom de l\'abonnement nécessaire',
                ],
            ],
        ],
        'invoicesList'       => [
            'name'        => 'Liste de factures',
            'description' => 'Liste toutes les facture d\'un utilisateur',
            'useSession'  => 'Utiliser la session utilisateur',
            'properties'  => [
                'userId'         => [
                    'title'       => 'ID de l\'utilisateur',
                    'description' => 'Affiche les factures pour cette utilisateur. Laissez vide pour utiliser l\'utilisateur connecté.',
                ],
                'includePending' => [
                    'title'       => 'Inclure les paiements en attente',
                    'description' => 'Inclure dans la liste les paiements en attente',
                ],
                'loadingText'    => [
                    'title'       => 'Texte de chargement',
                    'description' => 'Ce texte est affiché pendant que la liste des factures est récupérée depuis l\'API',
                ],
            ],
        ],
    ],
    'settings'   => [
        'invoice_vendor'            => 'Nom du vendeur',
        'invoice_vendor_comment'    => 'Ce nom sera affiché sur les factures PDF générés.',
        'invoice_product'           => 'Nom du  produit',
        'invoice_product_comment'   => 'Ce nom sera affiché sur les factures PDF générés.',
        'invoice_view'              => 'Fichier de vue de la facture',
        'invoice_view_comment'      => 'Renseigner une vue personnalisé pour utiliser votre propre fichier de vue.',
        'invoice_data'              => 'Données additionnelles à la facture (Numéro de téléphone, informations de contact, etc.)',
        'invoice_data_comment'      => 'Ces données seront inséré dans la facture.',
        'invoice_data_key'          => 'Nom de la variable',
        'invoice_data_value'        => 'Valeur de la variable',
        'currency_currency'         => 'Devise',
        'currency_currency_comment' => 'Sera utilisé par défaut pour toutes les opérations Cashier.',
        'currency_symbol'           => 'Symbole de la devise',
        'currency_symbol_comment'   => 'Le symbole est déterminé à l\'aide de la devise précédemment renseigné. Si tel n\'est pas le cas, une erreur sera affichée. Dans ce cas précisez le symbole manuellement.',
        'sections'                  => [
            'invoice'          => 'Factures PDF',
            'invoice_comment'  => 'Détermine l\'apparence des factures PDF générées.',
            'currency'         => 'Devise',
            'currency_comment' => 'Détermine quel devise Cashier doit utiliser par défaut.',
        ],
    ],
];