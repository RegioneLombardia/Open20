<?php

namespace open2\amos\ticket\controllers\api;

/**
* This is the class for REST controller "TicketFaqController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class TicketFaqController extends \yii\rest\ActiveController
{
public $modelClass = 'open2\amos\ticket\models\TicketFaq';
}
