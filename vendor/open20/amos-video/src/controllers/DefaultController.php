<?php

namespace open20\video\controllers;

use yii\web\Controller;

/**
 * Class DefaultController
 * @package frontend\modules\formazione\controllers
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {

        parent::init();
        // custom initialization code goes here
    }

    /**
     * Lists all community models.
     * @return mixed
     */
    public function actionIndex($layout = null)
    {
        return $this->redirect(['/video/video/all']);
    }
}

