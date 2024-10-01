<?php

namespace app\modules\cms\cmsadmin\controllers;

class DefaultController extends \luya\cms\admin\controllers\DefaultController {

    public $layout = false;

    public function init() {
        $this->viewPath = "@cmsadmin/admin/views/default";
        parent::init();
    }

    public function actionIndex() {
        return $this->render('@frontend/modules/cms/cmsadmin/views/default/index');
    }

}
