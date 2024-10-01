<?php

namespace open20\socialwall\controllers\api;

/**
 * This is the class for REST controller "SocialwallPostsProfilesController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SocialwallPostsProfilesController extends \yii\rest\ActiveController
{
    public $modelClass = 'open20\socialwall\models\SocialwallPostsProfiles';
}
