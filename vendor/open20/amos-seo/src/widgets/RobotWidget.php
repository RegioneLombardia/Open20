<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\seo
 * @category   CategoryName
 */

namespace open20\amos\seo\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use open20\amos\seo\AmosSeo;


class RobotWidget extends Widget
{

    public $model;
    public $modelClass;
    public $contentModel;
    public $metaRobotsList;
    public $metaGooglebotList;
    protected $form = null;

    
    public function init()
    {
        parent::init();
        
        $this->setMetaRobotsList();
        $this->setMetaGooglebotList();
    }


    public function run()
    {

        return $this->render('robot-tag', [
            'form' => $this->getForm(),
            'model' => $this->model,
            'modelClass' => $this->modelClass,
            'metaRobotsList' => $this->metaRobotsList,
            'metaGooglebotList' => $this->metaGooglebotList,
            'contentModel' => $this->contentModel
        ]);
    }

    /**
     * @return null
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param null $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    public function setMetaRobotsList()
    {
        $metaRobotsListConfig = Yii::$app->getModule(AmosSeo::getModuleName())->config['metaRobotsList'];
        foreach ($metaRobotsListConfig as $m) {
            $this->metaRobotsList[$m] = AmosSeo::t('amosseo', '#'.$m.'_desc');
        }
    }

    public function setMetaGooglebotList()
    {
        $metaGooglebotListConfig = Yii::$app->getModule(AmosSeo::getModuleName())->config['metaGooglebotList'];
        foreach ($metaGooglebotListConfig as $m) {
            $this->metaGooglebotList[$m] = AmosSeo::t('amosseo', '#'.$m.'_desc');
        }
    }


}