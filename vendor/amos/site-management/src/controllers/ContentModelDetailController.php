<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 04/09/2018
 * Time: 12:41
 */

namespace amos\sitemanagement\controllers;


use amos\sitemanagement\models\SiteManagementElement;
use amos\sitemanagement\models\SiteManagementLanding;
use open20\amos\core\controllers\BackendController;

class ContentModelDetailController extends BackendController
{
    public function init()
    {
        parent::init();
        $this->layout = 'main';
        $this->setUpLayout();
    }

    /**
     * @param $id
     * @return string
     */
    public function actionIndex($id){
        $element = SiteManagementElement::findOne($id);

    }
}