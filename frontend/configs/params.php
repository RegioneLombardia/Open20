<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

$googleMapsApiKey = '';

return [
    'versione' => '1.0.0',
    'user.passwordResetTokenExpire' => 86400 * 7,
    'google_places_api_key' => $googleMapsApiKey,
    'google-maps' => [
        'key' => $googleMapsApiKey
    ],
    'googleMapsApiKey' => $googleMapsApiKey,
    'google_recaptcha_site_key' => '',
    'google_recaptcha_secret' => '',
    'noWizardNewLayout' => true,
    'menuModules' => [],
    'befe' => true,
    'enablePositionalBreadcrumb' => true,
    'assistance' => [
        'enabled' => false,
        'type' => 'mail',
        'email' => 'ticket@test.example.it',
        'url' => ''
    ],
    'favicon' => '/img/faviconRL.png',
    'logoMail' => '/img/RL_logo.png',
    'logoConfigurations' => [
        'secondLogo' => [
            'logoText' => 'Open 2.0',
            'logoUrl' => '/site/to-menu-url?url=/',
            'logoTitle' => 'Vai alla homepage di Open 2.0',
            'logoClass' => 'logo-text-platform-design',
        ],
        'firstLogo' => [
            'logoImg' => '/img/RL_logo.png',
            'logoAlt' => 'logo Regione Lombardia',
            'logoUrl' => '/',
            'logoUrlTarget' => '_blank',
            'logoTitle' => 'Vai al portale di Regione Lombardia',
            'logoClass' => 'd-md-block logo-RL-design',
        ],
    ],
    'socialConfigurations' => [
        // 'linkedin' => 'https://linkedin.com',
    ],
    'menuCmsConfigurations' => [
        'footerCmsMenu' => 'footer',
        'footerCmsMenuUno' => 'footer-uno',
        'enableFooterSubNav' => false

    ],
    'loginAllowedIPs' => [
        '127.0.0.1'
    ],
];
