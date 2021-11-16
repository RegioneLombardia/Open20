<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

$config = [
    'params' => [
        //active the search 
        'searchParams' => [
            'discussioni-topic' => [
                'enable' => true,
            ]
        ],
        //active the order
        'orderParams' => [
            'discussioni-topic' => [
                'enable' => true,
                'fields' => [
                    'titolo',
                    'created_at'
                ],
                'default_field' => 'created_at',
                'order_type' => SORT_DESC
            ]
        ],
    ]
];
return $config;