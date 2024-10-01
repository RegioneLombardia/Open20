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
use open20\amos\events\models\search\EventSearch;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconEventsCreatedBy
 * @package open20\amos\events\widgets\icons
 */
class WidgetIconEventsCreatedBy extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosEvents::t('amosevents', 'Events created by me'));
        $this->setDescription(AmosEvents::t('amosevents', 'Events created by me'));
        $this->setIcon('calendar');
        $this->setUrl(['/events/event/created-by']);
        $this->setCode('EVENT_CREATED_BY');
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
    }
}