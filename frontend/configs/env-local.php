<?php

/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [
    'modules' => [
        'userauthfrontend' => [
            'enableSPID' => false,
        ],
    ],
    'components' => [
        'adminuser' => [
            'class' => 'app\modules\cms\components\AdminUser',
            'defaultLanguage' => 'it',
            'enableAutoLogin' => true,
            'enableSession' => true,
            'identityCookie' => [
                'name' => '_identity-cmsstage',
                'httpOnly' => true,
                'secure' => true,
                'domain' => "front-open20-github-forweb.stage.demotestwip.it",
            ],
        ],
        'user' => [
            'class' => 'open20\amos\core\user\AmosUser',
            'identityClass' => 'open20\amos\core\user\User',
            'loginUrl' => '/it/login',
            'enableAutoLogin' => true,
            'authTimeout' => 3600 * 12,
            'autoRenewCookie' => true,
            'enableSession' => true,
            'identityCookie' => [
                'name' => '_identity-openstage',
                'httpOnly' => true,
                'secure' => true,
                'domain' => "front-open20-github-forweb.stage.demotestwip.it",
            ],
        ],
    ],
];
