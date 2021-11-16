<?php

namespace backend\modules\userimporter\controllers\api;

/**
* This is the class for REST controller "UserImporterTaskController".
*/

use yii\rest\ActiveController;

class UserImportTaskController extends ActiveController
{
public $modelClass = 'backend\modules\userimporter\models\UserImportTask';
}
