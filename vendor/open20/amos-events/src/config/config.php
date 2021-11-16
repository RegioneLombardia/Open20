<?php

/**
 * Lombardia Informatica S.p.A.
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
            'event-group-referent' => [
                'enable' => true,
            ],
            'event' => [
                'enable' => true,
            ],
            'event-status' => [
                'enable' => true,
            ],
            'event-type' => [
                'enable' => true,
            ],
            'event-dashboard' => [
                'enable' => true,
            ]
        ]
    ]
];
