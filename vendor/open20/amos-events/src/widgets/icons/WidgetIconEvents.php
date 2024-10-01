<?php
/**
 * Aria S.p.A.
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
use open20\amos\utility\models\BulletCounters;
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
                $this->getClassSpan(), $paramsClassSpan
            )
        );

        if ($this->disableBulletCounters == false) {
            $widgetAll = \Yii::createObject(['class' => WidgetIconAllEvents::className(), 'saveMicrotime' => false]);
            $this->setBulletCount(
                $widgetAll->getBulletCount()
            );
        }
    }
}