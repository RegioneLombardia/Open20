<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\widgets\icons;

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\partnershipprofiles\Module;

use open20\amos\utility\models\BulletCounters;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconPartnershipProfilesDashboard
 * @package open20\amos\partnershipprofiles\widgets\icons
 */
class WidgetIconPartnershipProfilesDashboardGeneral extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-primary'
        ];

        $this->enableDashboardModal();
        $this->setLabel(Module::tHtml('amospartnershipprofiles', '#widget_icon_partnership_profiles_dashboard_label'));
        $this->setDescription(Module::t('amospartnershipprofiles', '#widget_icon_partnership_profiles_dashboard_description'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $customIcon = Module::instance()->pluginCustomIcon;
            if (strlen($customIcon) > 0) {
                $this->setIcon($customIcon);
            } else {
                $this->setIcon('propostecollaborazione');
            }
            $paramsClassSpan = [];
        } else {
            $customIcon = Module::instance()->pluginCustomIcon;
            if (strlen($customIcon) > 0) {
                $this->setIcon($customIcon);
            } else {
                $this->setIcon('partnership-profiles');
            }
        }

        $this->setUrl(['/partnershipprofiles']);
        $this->setCode('PARTNERSHIP_PROFILES_DASHBOARD');
        $this->setModuleName('partnershipprofiles');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

        // Read and reset counter from bullet_counters table, bacthed calculated!
        if ($this->disableBulletCounters == false) {
             $widgetAll = new WidgetIconPartnershipProfilesAll();
            $this->setBulletCount(
                $widgetAll->getBulletCount()
            );
        }
        
//        if ($this->disableBulletCounters == false) {
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId()
//                )
//            );
////            $this->trigger(self::EVENT_AFTER_COUNT);
//        }
    }

    /**
     * @param null $userId
     * @param null $className
     * @param null $externalQuery
     * @return string
     */
//    public function makeBulletCounter($userId = null, $className = null, $externalQuery = null)
//    {
//        $widgetAll = new WidgetIconPartnershipProfilesAll();
////        $widgetCreatedBy = new WidgetIconPartnershipProfilesCreatedBy();
//
//        return $widgetAll->getBulletCount(); // + $widgetCreatedBy->getBulletCount();
//    }
}
