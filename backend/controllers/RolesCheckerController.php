<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

namespace backend\controllers;


use yii\web\Controller;

class RolesCheckerController extends Controller
{

    public $layout = '@vendor/open20/amos-core/views/layouts/main';

    public function actionIndex()
    {
        $Roles = \Yii::$app->getAuthManager()->getRoles();

        return $this->render('index', [
            'Roles' => $Roles
        ]);


    }

}