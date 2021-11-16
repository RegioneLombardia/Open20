<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

$bootstrap = [];
$bootstrap[] = 'cms';
$bootstrap[] = 'workflow';

$bootstrap[] = 'frontend\bootstrap\CheckUser';


/*$bootstrap[] = [
    'class' => 'open20\amos\core\components\LanguageSelector',
    'supportedLanguages' => ['en-GB', 'it-IT'],
    'allowedIPs' => ['*']
];*/

return $bootstrap;
