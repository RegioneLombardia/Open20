<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

return [
    'params' => [
        //active the search
        'searchParams' => [
            'community' => true,
            'bookmarks' => true
        ],
        //active the order
        'orderParams' => [
            'community' => [
                'enable' => true,
                'fields' => [
                    'name',
                    'created_at'
                ],
                'default_field' => 'created_at',
                'order_type' => SORT_DESC,
            ],
            'bookmarks' => [
                'enable' => true,
                'fields' => [
                    'titolo',
                ],
                'order_type' => SORT_DESC,
            ]
        ]
    ]
];
