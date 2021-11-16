<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\operators\models 
 */

namespace open20\agid\person\controllers;
use open20\amos\core\helpers\Html;
use open20\agid\person\Module;


/**
 * Class AgidPersonTypeController 
 * This is the class for controller "AgidPersonTypeController".
 * @package backend\modules\operators\models 
 */
class AgidPersonTypeController extends \open20\agid\person\controllers\base\AgidPersonTypeController
{
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = 'Tipologia persona';
            $urlLinkAll   = '/person/agid-person-type/index';

            
        } else {
            $titleSection = 'Tipologia persona';
            
        }

        $labelCreate = 'Nuovo';
        $titleCreate = 'Crea un nuovo tipo di persona';
        $urlCreate   = '/person/agid-person-type/create';
      
        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'persone-type',
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
