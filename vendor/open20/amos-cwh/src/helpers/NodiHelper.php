<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\helpers;

class NodiHelper extends \yii\base\Component
{

    public static function text(\open20\amos\core\record\Record $model)
    {
        $recordPubblicato = \open20\amos\cwh\models\CwhConfig::getConfig($model->tableName());
        return $recordPubblicato;
    }

}