<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

$bootstrap[] = 'log';
$bootstrap[] = 'email';
$bootstrap[] = 'schema';
$bootstrap[] = 'socialauth';
$bootstrap[] = 'userimporter';
//$bootstrap[] = 'news';
$bootstrap[] = 'backend\modules\landings\bootstrap\AfterInsertNews';
$bootstrap[] = 'backend\modules\landings\bootstrap\AfterInsertDocumenti';
//$bootstrap[] = 'events';

return $bootstrap;
