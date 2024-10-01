<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    app\controllers 
 */
 
namespace open20\agid\dataset\controllers;
use open20\amos\core\helpers\Html;
use open20\agid\person\Module;

/**
 * Class AgidDatasetController 
 * This is the class for controller "AgidDatasetController".
 * @package app\controllers 
 */
class AgidDatasetController extends \open20\agid\dataset\controllers\base\AgidDatasetController
{
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosdataset', '#menu_front_dataset');
            $urlLinkAll   = '';

           
        } else {
            $titleSection = Module::t('amosdataset', '#menu_front_dataset');
            
        }

        $labelCreate = 'Nuovo';
        $titleCreate = 'Crea un nuovo dataset';
        $urlCreate   = '/dataset/agid-dataset/create';
      
        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'dataset',
            'titleSection' => $titleSection,
            'subTitleSection' => '',
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => '',
            'titleLinkAll' => '',
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'urlCreate' => $urlCreate,
            
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }
}
