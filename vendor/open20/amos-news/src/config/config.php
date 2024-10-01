<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

return [
    'params' => [
        'img-default' => '/img/defaultProfilo.png',
        'site_publish_enabled' => false,
        'site_featured_enabled' => false,
        //active the search
        'searchParams' => [
            'news' => [
                'enable' => true,
            ]
        ],

        //active the order
        'orderParams' => [
            'news' => [
                'enable' => false,
                'fields' => [
                    'titolo',
                    'data_pubblicazione'
                ],
                'default_field' => 'news.data_pubblicazione',
                'order_type' => SORT_DESC
            ]
        ],
        //active map search
        'mapParams' => [
            'news' => [
                'enable' => true,
            ]
        ],
        //active the introduction
        'introductionParams' => [
            'news' => [
                'enable' => true,
            ]
        ]
    ]
];
