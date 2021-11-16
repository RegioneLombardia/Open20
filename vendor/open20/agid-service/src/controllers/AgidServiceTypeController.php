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
 * Class AgidServiceTypeController
 * This is the class for controller "AgidServiceTypeController".
 * @package open20\agid\service\models
 */
class AgidServiceTypeController extends \open20\agid\service\controllers\base\AgidServiceTypeController
{
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosservice', 'Service type');
            $urlLinkAll   = '/service/agid-service-type/index';

           
        } else {
            $titleSection = Module::t('amosservice', 'Service type');
            
        }

        $labelCreate = 'Nuovo';
        $titleCreate = 'Crea un nuovo tipo di servizio';
        $urlCreate   = '/service/agid-service-type/create';
      
        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'service-type',
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
