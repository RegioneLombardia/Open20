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
$bootstrap[] = 'open20\amos\core\bootstrap\Breadcrumb';
$bootstrap[] = 'log';
$bootstrap[] = 'cms';
$bootstrap[] = 'design';
$bootstrap[] = 'layout';
$bootstrap[] = 'workflow';
$bootstrap[] = 'backendobjects';
$bootstrap[] = 'amosadmin';
$bootstrap[] = 'socialauth';
$bootstrap[] = 'chat';
$bootstrap[] = 'comments';
$bootstrap[] = 'seo';
$bootstrap[] = 'cwh';
$bootstrap[] =  'open20\amos\moodle\bootstrap\EventRoleUser';
$bootstrap[] = 'open20\amos\admin\bootstrap\UserLastOnlineBootstrap';

if (!function_exists('pr')) {
    function pr($var, $info = '', $depth = 10)
    {
        if (!defined('PR')) {
            define('PR', true);
        }
        if ($info) {
            $info = "<strong>$info: </strong>";
        }
        $debug = debug_backtrace(0);
        $result = "<strong>" . $debug[0]['file'] . " " . $debug[0]['line'] . "</strong><br /> $info <br />";
        print_r($result) . \yii\helpers\VarDumper::dump($var, $depth, true);
    }
}


return $bootstrap;
