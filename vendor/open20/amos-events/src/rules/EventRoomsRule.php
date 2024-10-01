<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\rules
 * @category   CategoryName
 */

namespace open20\amos\events\rules;

use open20\amos\events\AmosEvents;
use yii\rbac\Rule;

/**
 * Class EventRoomsRule
 * @package open20\amos\events\rules
 */
class EventRoomsRule extends Rule
{
    public $name = 'eventRooms';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        /** @var AmosEvents $eventsModule */
        $eventsModule = AmosEvents::instance();
        if (is_null($eventsModule)) {
            return false;
        }
        return $eventsModule->enableEventRooms;
    }
}
