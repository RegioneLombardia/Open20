<?php

namespace open20\amos\events\controllers\api;

/**
* This is the class for REST controller "EventAccreditationListController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class EventAccreditationListController extends \yii\rest\ActiveController
{
public $modelClass = 'open20\amos\events\models\EventAccreditationList';
}
