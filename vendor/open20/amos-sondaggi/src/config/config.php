<?php

return [
    'params' => [
        'searchParams' => [
            'sondaggi' => [
                'enable' => true,
            ]
        ],
    ],
    'modules' => [
        'v1' => [
            'class' => \open20\amos\sondaggi\modules\v1\V1::className()
        ]
    ],
];
