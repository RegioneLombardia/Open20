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

$bootstrap[] = 'translation';
$bootstrap[] = 'translatemanager';

$bootstrap[] = 'open20\amos\core\bootstrap\Breadcrumb';
$bootstrap[] = 'workflow';
$bootstrap[] = 'notify';
$bootstrap[] = 'mobilebridge';
$bootstrap[] = 'layout';
$bootstrap[] = 'seo';

$bootstrap[] = 'backend\bootstrap\CheckUser';

return $bootstrap;
