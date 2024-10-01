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
use open20\amos\events\AmosEvents;
//use open20\amos\events\models\Event;
//use open20\amos\events\models\search\EventSearch;
use open20\amos\utility\models\BulletCounters;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconEventOwnInterest
 * @package open20\amos\events\widgets\icons
 */
class WidgetIconEventOwnInterest extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosEvents::t('amosevents', '#widget_icon_event_own_interest_label'));
        $this->setDescription(AmosEvents::t('amosevents', '#widget_icon_event_own_interest_description'));
        $this->setIcon('calendar');
        $this->setUrl(['/events/event/own-interest']);
        $this->setCode('EVENT_OWN_INTEREST');
        $this->setModuleName('events');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                'bk-backgroundIcon',
                'color-lightPrimary'
                ]
            )
        );

        // Read and reset counter from bullet_counters table, bacthed calculated!
        if ($this->disableBulletCounters == false) {
            $this->setBulletCount(
                BulletCounters::getAmosWidgetIconCounter(
                    Yii::$app->getUser()->getId(), AmosEvents::getModuleName(), $this->getNamespace(),
                    $this->resetBulletCount(), WidgetIconAllEvents::className()
                )
            );
        }
    }
}