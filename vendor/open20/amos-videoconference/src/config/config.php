<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\videoconference
 * @category   CategoryName
 */

$jitsiDomain = "meet.test.demotestwip.it";

return [
    'config' => [
    ],
    'params' => [
        //active the search
        'searchParams' => [
            'videoconf' => [
                'enable' => true
            ]
        ],
        //active the order
        'orderParams' => [
            'videoconf' => [
                'enable' => true,
                'fields' => [
                    'title',
                    //'notification_before_conference'
                    //'begin_date_hour',
                    //'end_date_hour'
                ],
                'default_field' => 'videoconf.begin_date_hour',
                'order_type' => SORT_DESC
            ]
        ]
    ]
];