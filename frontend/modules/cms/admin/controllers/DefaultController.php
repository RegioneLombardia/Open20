<?php

namespace app\modules\cms\admin\controllers;

class DefaultController extends \luya\admin\controllers\DefaultController {

    //public $layout = '@frontend/modules/cms/admin/views/layouts/main';
    //public $layout = '@vendor/open20/design/src/views/layouts/bi-main-layout';
    public $layout = '@frontend/views/layouts/main';
     
    public function init() {
        $this->viewPath = "@admin/views/default";
        $this->view->params['angularCmsBackend'] = true;
        parent::init();
    }
   
    
}
