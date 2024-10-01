<?php

namespace amos\cmsbridge\widgets\icons;

use open20\amos\core\views\assets\AmosCoreAsset;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\layout\assets\BaseAsset;
use Yii;
use yii\helpers\ArrayHelper;

class WidgetIconCmsDashboard extends WidgetIcon {

    /**
     * @var Module $moduleCms
     */
    private $moduleCms = null;
       
    public function init() {
        parent::init();

        $this->moduleCms = Yii::$app->getModule('cmsbridge');
        $this->moduleCms->frontendUrl = Yii::$app->params['platform']['frontendUrl']; 
        
        $this->setLabel(\Yii::t('amos\cmsbridge\widgets\icons', 'Cms'));
        $this->setDescription(Yii::t('amos\cmsbridge\widgets\icons', 'Cms'));

        $this->setIcon('linmodulo');
        //$this->setIconFramework();

        /**
         * link to frontend
         * check params from platform/backend/config/params.php
         */
        // get params from platform/common/config/params-local.php
        $this->setUrl($this->moduleCms->frontendUrl . '/admin');
        
        $this->setCode('Cms');
        $this->setModuleName('Cms');
        $this->setNamespace(__CLASS__);
        //  $this->setBulletCount($this->getBulletCountChildWidgets());
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
                    'bk-backgroundIcon',
                    'color-primary'
        ]));
    }

    /**
     *
     * @return type
     */
    public function getHtml()
    {
        $controller = \Yii::$app->controller;
        $moduleL = \Yii::$app->getModule('layout');

        if (!empty($moduleL)) {
            $assetBundle = BaseAsset::register($controller->getView());
        } else {
            $assetBundle = AmosCoreAsset::register($controller->getView());
        }

        $view = '@vendor/amos/cmsbridge/src/widgets/icons/views/icon';
        if ($this->getEngine() == WidgetAbstract::ENGINE_ROWS) {
            $view = '@vendor/amos/cmsbridge/src/widgets/icons/views/icon_rows';
        }

        return $this->render(
            $view,
            [
                'asset' => $assetBundle,
                'widget' => $this
            ]
        );
    }

}
