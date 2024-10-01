<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\seo
 * @category   CategoryName
 */
return [
    'config' => [
        'metaRobotsList' => [
            'noindex',
            'nofollow',
            'nosnippet',
            'noarchive',
            'unavailable_after',
            'noimageindex'
        ],
        'metaGooglebotList' => [
            'noindex',
            'nofollow',
            'nosnippet',
            'noarchive',
            'unavailable_after',
            'noimageindex'
        ],
        'modulesEnabled' => [// TODO da capire se possiamo recuperarli in altra maniera
            'open20\amos\news\AmosNews',
            'open20\amos\discussioni\AmosDiscussioni',
            'open20\amos\documenti\AmosDocumenti',
            'open20\amos\community\AmosCommunity',
        ],
    ],
    'params' => [
        //active the search
        'searchParams' => [
        ],
        //active the order
        'orderParams' => [
        ],
    ]
];
