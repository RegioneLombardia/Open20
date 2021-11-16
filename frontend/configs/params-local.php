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
    'supportEmail' => 'assistenza@example.com',
    'assistance' => [
        'enabled' => true, //Enable the assistance button
        'type' => 'email', //Assistance button mode: url or email
        'email' => 'assistenza@example.com', //Assistance mail
        'url' => '/path/to/assistance/page' //Url to the assistance page
    ],
    'platform' => [
        'frontendUrl' => 'SITE_URL_HERE',
        'backendUrl' => 'SITE_URL_HERE',
    ],
    'google_recaptcha_site_key' => '',
    'google_recaptcha_secret' => '',
];
