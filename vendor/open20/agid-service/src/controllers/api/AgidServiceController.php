<?php

namespace open20\agid\service\models\api;

/**
* This is the class for REST controller "AgidServiceController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class AgidServiceController extends \yii\rest\ActiveController
{
public $modelClass = 'open20\agid\service\models\AgidService';
}
