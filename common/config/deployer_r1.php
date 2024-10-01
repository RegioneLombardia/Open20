<?php
    return [
        'params' => [
            'platform' => [
                'frontendUrl' => 'https://front-open20-github-forweb.stage.demotestwip.it',
            ],
            'dev-mode' => true
        ],
        'modules' => [
            'utility' => [
                'class' => 'open20\amos\utility\Module',
                'params' => [
                    'master_domain' => 'https://front-open20-github-forweb.stage.demotestwip.it'
                ]
            ]
        ]
    ];