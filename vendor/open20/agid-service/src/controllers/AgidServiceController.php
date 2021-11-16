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
 * Class AgidServiceController
 * This is the class for controller "AgidServiceController".
 * @package open20\agid\service\controllers
 */
class AgidServiceController extends \open20\agid\service\controllers\base\AgidServiceController
{
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosservice', 'grammar_model_plural');
            $urlLinkAll   = '/service/agid-service/index';

           
        } else {
            $titleSection = Module::t('amosservice', 'grammar_model_plural');
            
        }

        $labelCreate = 'Nuovo';
        $titleCreate = 'Crea un nuovo servizio';
        $urlCreate   = '/service/agid-service/create';
      
        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'servizi',
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
