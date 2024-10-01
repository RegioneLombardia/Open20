<?php

namespace open20\amos\collaborations\controllers;


use open20\amos\dashboard\controllers\base\DashboardController;

class DefaultController extends DashboardController
{

    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        return $this->redirect('/collaborations/collaboration-proposals/index');

    }

}