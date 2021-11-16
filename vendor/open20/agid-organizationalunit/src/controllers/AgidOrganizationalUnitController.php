<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\organizationalunit\controllers
 */

namespace open20\agid\organizationalunit\controllers;
use open20\amos\core\helpers\Html;
use open20\agid\person\Module;

/**
 * Class AgidOrganizationalUnitController
 * This is the class for controller "AgidOrganizationalUnitController".
 * @package app\controllers
 */
class AgidOrganizationalUnitController extends \open20\agid\organizationalunit\controllers\base\AgidOrganizationalUnitController
{
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosorganizationalunit', '#menu_front_organizationalunit');
            $urlLinkAll   = '';

           
        } else {
            $titleSection = Module::t('amosorganizationalunit', '#menu_front_organizationalunit');
            
        }

        $labelCreate = 'Nuova';
        $titleCreate = 'Crea una nuova unità organizzativa';
        $urlCreate   = '/organizationalunit/agid-organizational-unit/create';
      
        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'persone',
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
