<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\config
 * @category   CategoryName
 */

return [
    'components' => [
        // List of component configurations
        'formatter' => [
            'class' => 'open20\amos\core\formatter\Formatter',
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '',
            'secret' => 'Z01Ak_2017',
        ],
    ],
    'params' => [
        // Active the search
        'searchParams' => [
            'user-profile' => [
                'enable' => true
            ]
        ],
        
        // Active the order
        'orderParams' => [
            'user-profile' => [
                'enable' => true,
                'fields' => [
                    'nome',
                    'cognome',
                    'surnameName',
                    'prevalentPartnership',
                    'created_at'
                ],
                'default_field' => 'surnameName',
                'order_type' => SORT_ASC
            ]
        ]
    ]
];
