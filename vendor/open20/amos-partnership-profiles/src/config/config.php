<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\config
 * @category   CategoryName
 */

return [
    'params' => [
        // Activate the search
        'searchParams' => [
            'partnership-profiles' => [
                'enable' => true
            ],
            'expressions-of-interest' => [
                'enable' => true
            ]
        ],

        // Activate the order
        'orderParams' => [
            'partnership-profiles' => [
                'enable' => true,
                'fields' => [
                    'partnership_profiles.title',
                    'updated_at',
//                    'short_description',
                    'partnership_profile_date',
                    // TODO rimane da implementare il campo relativo all'ordinamento per data di scadenza.
                ],
                'default_field' => 'updated_at',
                'order_type' => SORT_DESC
            ],
//            'expressions-of-interest' => [
//                'enable' => true,
//                'fields' => [
////                    'status',
//                    'partnershipProfile.title',
//                ],
//                'default_field' => 'id',
//                'order_type' => SORT_ASC
//            ]
        ]
    ]
];
