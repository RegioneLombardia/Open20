<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\events\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;

use open20\amos\events\AmosEvents;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconEvents
 * @package open20\amos\events\widgets\icons
 */
class WidgetIconEvents extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-lightPrimary'
        ];

        $this->setLabel(AmosEvents::t('amosevents', '#widget_icon_events_label'));
        $this->setDescription(AmosEvents::t('amosevents', 'Allow the user to modify the Events entity'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('eventi');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('calendar');
        }

        $this->setUrl(['/events']);
        $this->setCode('EVENTS');
        $this->setModuleName('events');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

        if ($this->disableBulletCounters == false) {
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
//        $widgetAll = new WidgetIconEventOwnInterest();
//        $widgetCreatedBy = new WidgetIconEventsCreatedBy();
//
//        return $widgetAll->getBulletCount() + $widgetCreatedBy->getBulletCount();

        $widgetAll = new WidgetIconAllEvents();

        return $widgetAll->getBulletCount();
    }

}
