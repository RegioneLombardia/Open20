<?php

namespace open20\amos\community\controllers\api;

/**
* This is the class for REST controller "BookmarksController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class CommunityLinksController extends \yii\rest\ActiveController
{
public $modelClass = 'open20\amos\community\models\Bookmarks';
}
