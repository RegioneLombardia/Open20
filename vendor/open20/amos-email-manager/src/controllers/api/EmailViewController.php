<?php

namespace open20\amos\emailmanager\controllers\api;

/**
 * This is the class for REST controller "EmailViewController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class EmailViewController extends ActiveController
{
    public $modelClass = 'open20\amos\emailmanager\models\EmailView';
}
