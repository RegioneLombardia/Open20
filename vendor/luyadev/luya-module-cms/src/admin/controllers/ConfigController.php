<?php

namespace luya\cms\admin\controllers;

use luya\admin\base\Controller;

/**
 * Config Controller.
 *
 * @since 1.0.0
 */
class ConfigController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
