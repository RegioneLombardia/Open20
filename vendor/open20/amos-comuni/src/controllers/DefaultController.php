<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

namespace open20\amos\comuni\controllers;

use open20\amos\comuni\controllers\base\AjaxController;
use yii\web\Controller;


class DefaultController extends AjaxController
{
    public function actionIndex()
    {
        return $this->redirect(['/comuni/dashboard/index']);
    }
}