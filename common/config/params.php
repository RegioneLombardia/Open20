<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

return [
//    'supportEmail' => 'stefano.pavani@open20.it',
    'favicon'=> '/img/favicon.png',
    'logoMail' => '/img/logo_regione_lombardia.png',
    // 'logoConfigurations' => [
    //     'firstLogo' => [
    //         'logoImg' => '/img/logo-li.svg',
    //         'logoAlt' => 'logo Lombardia Informa',
    //         'logoUrl' => 'frontendUrl',
    //         'logoTitle' => 'Vai alla homepage',
    //     ],
    //     'topLogo' => [
    //         'logoImg' => '/img/logo_regione_lombardia-bianco.svg',
    //         'logoAlt' => 'logo Regione Lombardia',
    //         'logoClass' => 'd-xs-block logo-rl-top',
    //         'logoUrl' => 'https://regione.lombardia.it',
    //         'logoTitle' => 'Vai al portale di Regione Lombardia',
    //         'logoUrlTarget' => '_blank',
    //         'positionTop' => true,
    //     ]
    // ],
    'layoutConfigurations' => [
        'customPlatformFooter' => '@common/views/layouts/parts/footer-custom',
        'customUserMenuHeader' => '@common/views/layouts/parts/header-usermenu-custom',
//        'customUserNotLoggedHeader' => '@common/views/layouts/parts/header-user-notlogged-custom',
        'hideHamburgerMenuHeader' => true,
        'showAlwaysHamburgerMenuHeader' => false,
        'hideLangSwitchMenuHeader' => true,
        'hideGlobalSearchHeader' => true,
        'hideUserMenuHeader' => false,
        'hideSpidDescriptionLogin' => true,
        'hideIdpcButtonInfo' => true,
        'hideCookieBar' => true,
        'showLiteModeLogin' => false,
        'showSocialHeader' => false,
        'disableThemeLightHeader' => false,
    ],
    'linkConfigurations' => [
        'privacyPolicyLinkCommon' => '/it/privacy-policy',
        'cookiePolicyLinkCommon' => '/it/cookie-policy',
        'registrationLinkCommon' => '/preferenceuser/registration/preferences',
        'loginLinkCommon' => '/socialauth/shibboleth/endpoint'
    ],
    'mailup' => [
        'transactional-sms' => [
            'endpoint' => 'https://sendsms.mailup.com',
            'account-id' => '',
            'list-id' => '',
            'ListGuid' => '',
            'ListSecret' => '',
            'Sender' => '',
        ],
        'newsletter' => [
            'list-id' => 1
        ],
        'service-account' => [
            'client_id' => "",
            'client_secret' => "",
            'username' => "",
            'password' => "",
            'list-id' => 1,
        ]
    ],
    'platformConfigurations' => [
        'guestUserId' => 0,
    ]
];
