<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\community\widgets\icons;

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetIcon;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconCommunityDashboard
 * @package open20\amos\community\widgets\icons
 */
class WidgetIconCommunityDashboard extends WidgetIcon
{
    /*
     * to avoid multiple calling
     */
    protected static $_called = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosCommunity::tHtml('amoscommunity', '#widget_icon_community_dashboard_label'));
        $this->setDescription(AmosCommunity::t('amoscommunity', '#widget_icon_community_dashboard_description'));

        $paramsClassSpan = [
            'bk-backgroundIcon',
        ];

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('community');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('group');
        }

        $url     = ['/community'];
        $scopeId = $this->checkScope('community');
        if ($scopeId !== false) {
            $url = ['/community/subcommunities/my-communities', 'id' => $scopeId];
            $this->setLabel(AmosCommunity::tHtml('amoscommunity', '#widget_subcommunities_title'));
            $this->setDescription(AmosCommunity::t('amoscommunity', '#widget_subcommunities_description'));
        }
        $this->setUrl($url);

        $this->setCode('COMMUNITY_MODULE');
        $this->setModuleName('community-dashboard');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(), $paramsClassSpan
            )
        );

        // To avoid multiple call
        if (self::$_called === false) {
            self::$_called = true;
            return;
        }

        if ($this->disableBulletCounters == false) {
            $widgetAll = \Yii::createObject(['class' => WidgetIconCommunity::className(), 'saveMicrotime' => false]);
            $this->setBulletCount(
                $widgetAll->getBulletCount()
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function isVisible()
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        /** @var AmosCommunity $moduleCommunity */
        $moduleCommunity = \Yii::$app->getModule('community');
        $classnamesEnabled = $moduleCommunity->enableSubcommunitiesForNewtworks ;
        if (isset($moduleCwh)) {
            /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
            if (!empty($moduleCwh->getCwhScope())) {
                $scope = $moduleCwh->getCwhScope();
                if (isset($scope['community'])) {
                    /** @var Community $communityModel */
                    $communityModel = $moduleCommunity->createModel('Community');
                    /** @var Community $community */
                    $community = $communityModel::findOne($scope['community']);
                    if (!is_null($community) && ($community->context == $moduleCommunity->model('Community') || in_array($community->context, $classnamesEnabled))) {
                        return parent::isVisible();
                    }

                    return false;
                }
            }
        }

        return parent::isVisible();
    }

    /**
     * @return string
     */
    public static function widgetLabel()
    {
        return AmosCommunity::t('amoscommunity', 'Community dashboard');
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     *
     * @return array|\open20\amos\core\widget\type
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
                parent::getOptions(), ['children' => $this->getWidgetsIcon()]
        );
    }
    
    /**
     * @return array
     */
    public function getWidgetsIcon()
    {
        $widgets = [];

        $WidgetIconNewsCategorie = new WidgetIconCommunity();
        if ($WidgetIconNewsCategorie->isVisible()) {
            $widgets[] = $WidgetIconNewsCategorie->getOptions();
        }

        $WidgetIconNewsCreatedBy = new WidgetIconTipologiaCommunity();
        if ($WidgetIconNewsCreatedBy->isVisible()) {
            $widgets[] = $WidgetIconNewsCreatedBy->getOptions();
        }

        return $widgets;
    }
}
