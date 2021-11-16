<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\operators\models 
 */

namespace open20\agid\person\controllers;
/**
 * Class AgidPersonContentTypeController 
 * This is the class for controller "AgidPersonContentTypeController".
 * @package backend\modules\operators\models 
 */
class AgidPersonContentTypeController extends \open20\agid\person\controllers\base\AgidPersonContentTypeController
{
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = 'Content type persona';
            $urlLinkAll   = '/person/agid-person-content-type/index';

            
        } else {
            $titleSection = 'Content type persona';
            
        }

        $labelCreate = 'Nuovo';
        $titleCreate = 'Crea un nuovo content type';
        $urlCreate   = '/person/agid-person-content-type/create';
      
        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'persone-content-type',
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
