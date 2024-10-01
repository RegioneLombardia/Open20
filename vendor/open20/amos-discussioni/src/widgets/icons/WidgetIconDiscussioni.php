<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

namespace open20\amos\discussioni\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\models\AmosUserDashboards;
use open20\amos\dashboard\models\AmosUserDashboardsWidgetMm;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\discussioni\AmosDiscussioni;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Application as Web;

/**
 * Class WidgetIconDiscussioni
 * This widget can appear on dashboard. This class is used for creation and general configuration.
 * widget that link to the discussion dashboard
 * @deprecated
 * @package open20\amos\discussioni\widgets\icons
 */
class WidgetIconDiscussioni extends WidgetIcon
{

    /**
     * Init of the class, set of general configurations
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-primary'
        ];

        $this->setLabel(AmosDiscussioni::tHtml('amosdiscussioni', 'Discussioni'));
        $this->setDescription(AmosDiscussioni::t('amosdiscussioni', 'Modulo discussioni'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('disc');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('comment');
        }

        $this->setUrl(['/discussioni']);
        $this->setCode('DISCUSSIONI_MODULE_001');
        $this->setModuleName('discussioni-dashboard');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

        if (Yii::$app instanceof Web) {
            $this->setBulletCount(
                $this->makeBulletCounter(
                    Yii::$app->getUser()->getId()
                )
            );
        }
    }

    /**
     * 
     * @param type $userId
     * @param type $className
     * @param type $externalQuery
     * @return type
     */
    public function makeBulletCounter($userId = null, $className = null, $externalQuery = null)
    {
        return $this->getBulletCountChildWidgets($userId);
    }

    /**
     * 
     * @param type $user_id
     * @return int - the sum of bulletCount internal widget
     */
    private function getBulletCountChildWidgets($userId = null)
    {
        /** @var AmosUserDashboards $userModuleDashboard */
        $userModuleDashboard = AmosUserDashboards::findOne([
            'user_id' => $userId,
            'module' => AmosDiscussioni::getModuleName()
        ]);

        if (is_null($userModuleDashboard)) {
            return 0;
        }

        $listWidgetChild = $userModuleDashboard->amosUserDashboardsWidgetMms;
        if (is_null($listWidgetChild)) {
            return 0;
        }

        /** @var AmosUserDashboardsWidgetMm $widgetChild */
        $count = 0;
        
        $classNames = [];
        foreach ($listWidgetChild as $widgetChild) {
            if ($widgetChild->amos_widgets_classname != $this->getNamespace()) {
                $classNames[] = $widgetChild->amos_widgets_classname;
            }
        }
        
        $amosWidgets = AmosWidgets::find([
            'classname' => $classNames,
            'type' => AmosWidgets::TYPE_ICON
        ]);
        
        foreach ($amosWidgets as $widgetChild) {
            $widget = \Yii::createObject($widgetChild->amos_widgets_classname);
            $count += (int)$widget->getBulletCount();
        }

        return $count;
    }

    /**
     * all widgets added to the container object retrieved from the module controller
     * @return array
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => $this->getWidgetsIcon()]
        );
    }

    /**
     * @todo TEMPORARY
     */
    public function getWidgetsIcon()
    {
        $widgets = [];

        $WidgetIconDiscussioniTopicc = new WidgetIconDiscussioniTopic();
        if ($WidgetIconDiscussioniTopicc->isVisible()) {
            $widgets[] = $WidgetIconDiscussioniTopicc->getOptions();
        }

        $WidgetIconDiscussioniTopicCreatedBy = new WidgetIconDiscussioniTopicCreatedBy();
        if ($WidgetIconDiscussioniTopicCreatedBy->isVisible()) {
            $widgets[] = $WidgetIconDiscussioniTopicCreatedBy->getOptions();
        }

        return $widgets;
    }

}
