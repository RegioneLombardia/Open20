<?php
namespace open20\amos\utility\controllers;

use open20\amos\utility\Module;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\console\Controller;
use Yii;

class TasksManagerController extends Controller
{
    public function actionIndex()
    {
        echo "Tasks commands:\n";
        echo "\tlist - A list of tasked actions\n";
        echo "\trun  - Execute tasked actions\n";
    }

    public function actionList() {
        echo "None as of now\n";
    }

    public function actionRun() {
        echo "None as of now\n";
    }
}