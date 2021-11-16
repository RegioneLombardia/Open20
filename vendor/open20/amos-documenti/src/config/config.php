<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti
 * @category   CategoryName
 */

return [
    'params' => [
        'img-default' => '/img/defaultProfilo.png',
        'site_publish_enabled' => false,
        'site_featured_enabled' => false,
        //active the search
        'searchParams' => [
            'documenti' => [
                'enable' => true,
            ]
        ],
        //active the order
        'orderParams' => [
            'documenti' => [
                'enable' => false,
                'fields' => [
                    'titolo',
                    'data_pubblicazione'
                ],
                'default_field' => 'data_pubblicazione',
                'order_type' => SORT_DESC
            ]
        ],
    ]
];
