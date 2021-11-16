<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle
 * @category   CategoryName
 */

return [
    'config' => [
        'moodleTimezoneServer' => '99',
        'moodleAuthName' => 'manual',

    ],
    'params' => [
        //active the search
        'searchParams' => [
            'course-enrolment' => [
                'enable' => true,
            ],
        ],
        //active the order
        'orderParams' => [
        ],
    ]
];
