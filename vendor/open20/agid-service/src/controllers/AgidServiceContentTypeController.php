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
 * Class AgidServiceContentTypeController
 * This is the class for controller "AgidServiceContentTypeController".
 * @package open20\agid\service\models
 */
class AgidServiceContentTypeController extends \open20\agid\service\controllers\base\AgidServiceContentTypeController
{
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosservice', 'Service content type');
            $urlLinkAll   = '/service/agid-service-content-type/index';

           
        } else {
            $titleSection = Module::t('amosservice', 'Service content type');
            
        }

        $labelCreate = 'Nuovo';
        $titleCreate = 'Crea una nuovo content-type di servizio';
        $urlCreate   = '/service/agid-service-content-type/create';
      
        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'service-content-type',
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
