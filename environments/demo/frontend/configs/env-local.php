<?php

/* 
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [

    'components' => [
        'user' => [
            'class' => 'open20\amos\core\user\AmosUser',
            'identityClass' => 'open20\amos\core\user\User',
            'loginUrl' => '/it/login',
            'enableAutoLogin' => true,
            'authTimeout' => 3600 * 12,
            'autoRenewCookie' => true,
            'enableSession' => true,
        ],
           
    ],
];