<?php
namespace app\controllers;

use open20\amos\core\applications\CmsBoot;
use yii\console\Controller;

class UtilityController extends Controller
{

    public function actionEncryptValue($data)
    {
        $boot = new CmsBoot();
        var_dump($boot->encryptValue($data));
    }
}

