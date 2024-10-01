<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\controllers
 */

namespace open20\amos\partnershipprofiles\controllers;

use open20\amos\core\helpers\Html;
use open20\amos\partnershipprofiles\Module;


/**
 * Class PartnershipProfilesCategoryController
 * This is the class for controller "PartnershipProfilesCategoryController".
 * @package open20\amos\partnershipprofiles\controllers
 */
class PartnershipProfilesCategoryController extends \open20\amos\partnershipprofiles\controllers\base\PartnershipProfilesCategoryController
{


    public function beforeAction($action)
    {
        $titleSection = Module::t('amospartnershipprofiles', 'Le categorie');
        $labelLinkAll = Module::t('amospartnershipprofiles', 'Proposte di mio interesse');
        $urlLinkAll = Module::t('amospartnershipprofiles',
            '/partnershipprofiles/partnership-profiles/own-interest');
        $titleLinkAll = Module::t('amospartnershipprofiles', 'Visualizza la lista delle proposte di mio interesse');
        $subTitleSection = Html::tag('p', '');

        $labelCreate = Module::t('amospartnershipprofiles', 'Nuova');
        $titleCreate = Module::t('amospartnershipprofiles', 'Crea una nuova categoria');
        $labelManage = Module::t('amospartnershipprofiles', 'Gestisci');
        $titleManage = Module::t('amospartnershipprofiles', 'Gestisci le proposte');
        $urlCreate = '/partnershipprofiles/partnership-profiles-category/create';
        $urlManage = '#';

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'news',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }

    /**
     * @return array
     */
    public static function getManageLinks(){
        return \open20\amos\partnershipprofiles\controllers\PartnershipProfilesController::getManageLinks();
    }

}
