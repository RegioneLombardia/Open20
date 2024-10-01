<?php

namespace luya\cms\admin\controllers;

use luya\admin\base\Controller;

/**
 * Permission Controller.
 *
 * @since 1.0.0
 */
class PermissionController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
