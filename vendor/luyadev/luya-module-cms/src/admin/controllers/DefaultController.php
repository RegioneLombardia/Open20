<?php

namespace luya\cms\admin\controllers;

use luya\admin\base\Controller;

/**
 * Default Controller.
 *
 * @since 1.0.0
 */
class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
