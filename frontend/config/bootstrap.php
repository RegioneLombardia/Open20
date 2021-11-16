<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

$bootstrap = [];

$bootstrap[] = 'translatemanager';

$bootstrap[] = [
    'class' => 'open20\amos\core\components\LanguageSelector',
    'supportedLanguages' => ['en-GB', 'it-IT'],
    'allowedIPs' => ['*']
];

return $bootstrap;
