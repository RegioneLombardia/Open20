<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events
 * @category   CategoryName
 */

return [
    'params' => [
        'site_publish_enabled' => false,
        'site_featured_enabled' => false,

        //active the search 
        'searchParams' => [
            'event' => [
                'enable' => true,
            ],
            'event-room' => [
                'enable' => true,
            ],
            'event-status' => [
                'enable' => true,
            ],
            'event-type' => [
                'enable' => true,
            ]
        ]
    ]
];
