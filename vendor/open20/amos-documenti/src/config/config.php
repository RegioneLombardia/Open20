<?php

/**
 * Aria S.p.A.
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
                'enable' => true,
                'fields' => [
                    'titolo',
                    //'data_pubblicazione',
                    'updated_at',
                ],
                'default_field' => 'titolo',
                'order_type' => SORT_ASC
            ]
        ],
    ]
];
