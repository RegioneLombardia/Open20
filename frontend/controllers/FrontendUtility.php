<?php

namespace app\controllers;

use open20\amos\comuni\models\IstatProvince;
use yii\helpers\ArrayHelper;

class FrontendUtility
{

    public static function getIstatProvince()
    {
        return ArrayHelper::map(IstatProvince::find()->orderBy('nome')->asArray()->all(), 'id', 'nome');
    }
}