<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)).'/frontend');
Yii::setAlias('@console', dirname(dirname(__DIR__)).'/console');
Yii::setAlias('@app', dirname(dirname(__DIR__)).'/frontend');

function pr($var, $info = '', $depth = 10)
{
    if (!defined('PR')) {
        define('PR', true);
    }
    if ($info) {
        $info = "<strong>$info: </strong>";
    }
    $debug  = debug_backtrace(0);
    $result = "<strong>".$debug[0]['file']." ".$debug[0]['line']."</strong><br /> $info <br />";
    print_r($result).\yii\helpers\VarDumper::dump($var, $depth, true);
}
