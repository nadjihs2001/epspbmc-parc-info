<?php

return [
    'supportedLocales' => [
        'fr' => [
            'name' => 'French',
            'script' => 'Latn',
            'native' => 'Français',
            'regional' => 'fr_FR',
        ],
        'ar' => [
            'name' => 'Arabic',
            'script' => 'Arab',
            'native' => 'العربية',
            'regional' => 'ar_DZ',
        ],
        'en' => [
            'name' => 'English',
            'script' => 'Latn',
            'native' => 'English',
            'regional' => 'en_US',
        ],
    ],

    'useAcceptLanguageHeader' => true,

    'hideDefaultLocaleInURL' => true,

    'localesOrder' => ['fr', 'ar', 'en'],
];
