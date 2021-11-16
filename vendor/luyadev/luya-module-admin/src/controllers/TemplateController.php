<?php

namespace luya\admin\controllers;

use luya\admin\base\Controller;

/**
 * TemplateController renders angular templates.
 *
 * @since 1.0.0
 */
class TemplateController extends Controller
{
    public $disablePermissionCheck = true;

    public function actionDefault()
    {
        return $this->render('default');
    }
}
