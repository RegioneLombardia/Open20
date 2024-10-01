<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\config
 * @category   CategoryName
 */

return [
    'params' => [
        'img-default' => '/img/defaultProfilo.png',
        'site_publish_enabled' => false,
        'site_featured_enabled' => false,
        //active the search
        'searchParams' => [
            'ticket' => [
                'enable' => true,
            ],
            'ticket-categorie' => [
                'enable' => true,
            ],
        ],

        //active the order
        /* 'orderParams' => [
             'ticket' => [
                 'enable' => true,
                 'fields' => [
                     'titolo',
                     'data_pubblicazione'
                 ],
                 'default_field' => 'data_pubblicazione',
                 'order_type' => SORT_DESC
             ]
         ],*/

        //active the introduction
        /* 'introductionParams' => [
             'ticket' => [
                 'enable' => true,
             ]
         ]*/
    ]
];
