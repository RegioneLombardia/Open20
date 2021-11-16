<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

return [
    'email-assistenza' => 'help@example.com',
    'icon-framework' => 'am',
    'googleMapsLibraries'=> null,
    'googleMapsLanguage' =>'en',

    // Enable Amos Exclusive features
    'template-amos' => FALSE,

    // Enable template slideshow
    'slideshow' => TRUE,
    'slideshow-label' => 'Mostra introduzione', // TODO translate and amos-XXX::[t()|tHtml()] ?

    // Enable Localization menu
    'languageSelector' => TRUE,
    'allLanguages' => ['Italiano' => 'it-IT', 'English' => 'en-GB'],

    //enable btn dashboard to frontend
    'toFrontendLink' => false,

    //diable wizard in create content models (News, Documents, Discussions)
    'noWizardNewLayout' => true,
    'dashboardEngine' => 'rows',
    'hideSettings' => true,
    'hideScopeinAction' => ['events/event/view', 'events/event/update'],

    // LOGOS
    'favicon'=> 'img/favicon.png',
    'logoMail' => '/img/logo_regione_lombardia.png',
    'logoConfigurations' => [
        'firstLogo' => [
            'logoImg' => '/img/logo_regione_lombardia.svg',
            'logoAlt' => 'Logo Regione Lombardia',
            'logoUrl' => 'https://regione.lombardia.it',
            'logoUrlTarget' => '_blank',
            'logoTitle' => 'vai al sito Regione Lombardia',
            //'logoText' => 'Lombardia Informa'
        ],
        'secondLogo' => [
            'logoText' => 'Lombardia Informa',
        ],
    ],
    'user.passwordResetTokenExpire' => 86400,
    'seeLoginFormAllowedIps' => [
        '127.0.0.1',
        '172.17.0.1',
    ],
];
