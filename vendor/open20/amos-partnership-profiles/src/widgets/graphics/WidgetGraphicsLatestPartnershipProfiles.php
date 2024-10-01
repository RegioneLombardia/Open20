<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\amos\partnershipprofiles\models\search\PartnershipProfilesSearch;
use open20\amos\partnershipprofiles\Module;
use Yii;

/**
 * Class WidgetGraphicsLatestPartnershipProfiles
 * @package open20\amos\partnershipprofiles\widgets\graphics
 */
class WidgetGraphicsLatestPartnershipProfiles extends WidgetGraphic
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(\Yii::t('amospartenershipprofiles', 'Latest partnership profiles'));
        $this->setDescription(Yii::t('amospartenershipprofile', 'Latest partnership profiles'));
    }

    /**
     * rendering of the view ultime_discussioni
     *
     * @return string
     */
    public function getHtml()
    {
        /** @var PartnershipProfilesSearch $modelSearch */
        $modelSearch = Module::instance()->createModel('PartnershipProfilesSearch');
        $listaPartenership = $modelSearch->latestPartenershipProfilesSearch($_GET, Module::MAX_LAST_PARTNERSHIP_ON_DASHBOARD);

        $viewToRender = 'latest_partenership_profiles';

        if (is_null(\Yii::$app->getModule('layout'))) {
            $viewToRender = 'latest_partenership_profiles_old';
        }

        return $this->render($viewToRender, [
            'listaPartnership' => $listaPartenership,
            'widget' => $this,
            'toRefreshSectionId' => 'widgetGraphicLatestThreads'
        ]);
    }
}
