<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\controllers
 * @category   CategoryName
 */

namespace open20\agid\service\controllers;
use open20\amos\core\helpers\Html;
use open20\agid\person\Module;

/**
 * Class AgidServiceStatusController
 * This is the class for controller "AgidServiceStatusController".
 * @package open20\agid\service\models
 */
class AgidServiceStatusController extends \open20\agid\service\controllers\base\AgidServiceStatusController
{
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosservice', 'Service status');
            $urlLinkAll   = '/service/agid-service-status/index';

           
        } else {
            $titleSection =  Module::t('amosservice', 'Service status');
            
        }

        $labelCreate = 'Nuovo';
        $titleCreate = 'Crea una nuovo status per servizio';
        $urlCreate   = '/service/agid-service-status/create';
      
        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'service-status',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
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
