<?php

namespace open20\agid\service\models\api;

/**
* This is the class for REST controller "AgidServiceStatusController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class AgidServiceStatusController extends \yii\rest\ActiveController
{
public $modelClass = 'open20\agid\service\models\AgidServiceStatus';
}
