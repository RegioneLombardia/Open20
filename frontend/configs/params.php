<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */
$googleMapsApiKey = 'AIzaSyAFc8ESM0Rv33ruzSLTaVEPwxTfzCRcU94';

return [
//    'supportEmail' => 'stefano.pavani@open20.it',
    'versione' => '1.2.16', // Version
    'user.passwordResetTokenExpire' => 86400,
    'google-maps' => [
        'key' => $googleMapsApiKey
    ],
    'platformConfigurations' => [
        'guestUserId' => 2
    ],
    'logoConfigurations' => [
        'firstLogo' => [
            'logoImg' => '/img/logo-li.svg',
            'logoAlt' => 'logo Lombardia Informa',
            'logoUrl' => 'frontendUrl',
            'logoTitle' => 'Vai alla homepage',
        ],
        'topLogo' => [
            'logoImg' => '/img/logo_regione_lombardia-bianco.svg',
            'logoAlt' => 'logo Regione Lombardia',
            'logoClass' => 'd-xs-block logo-rl-top',
            'logoUrl' => 'https://regione.lombardia.it',
            'logoTitle' => 'Vai al portale di Regione Lombardia',
            'logoUrlTarget' => '_blank',
            'positionTop' => true,
        ]
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
];
