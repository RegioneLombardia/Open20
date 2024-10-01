<?php

namespace open20\socialwall\controllers\api;

/**
* This is the class for REST controller "SocialwallTokensController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SocialwallTokensController extends \yii\rest\ActiveController
{
public $modelClass = 'open20\socialwall\models\SocialwallTokens';
}
