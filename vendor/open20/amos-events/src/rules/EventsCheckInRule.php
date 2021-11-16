<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\rules
 * @category   CategoryName
 */

namespace open20\amos\events\rules;

use open20\amos\community\models\CommunityUserMm;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use yii\rbac\Rule;

/**
 * Class EventsCheckInRule
 * @package open20\amos\events\rules
 */
class EventsCheckInRule extends Rule
{
    public $name = 'EventsCheckIn';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $get = \Yii::$app->getRequest()->get();
        if (array_key_exists('eid', $get)) {
            /** @var AmosEvents $eventsModule */
            $eventsModule = AmosEvents::instance();
            if (is_null($eventsModule)) {
                return false;
            }
            /** @var Event $eventModel */
            $eventModel = $eventsModule->createModel('Event');
            $event = $eventModel::findOne(['id' => $get['eid']]);
            if ($event) {
                return $this->ruleLogic($user, $item, $params, $event);
            }
        } elseif (array_key_exists('communityId', $get)) {
            /** @var AmosEvents $eventsModule */
            $eventsModule = AmosEvents::instance();
            if (is_null($eventsModule)) {
                return false;
            }
            /** @var Event $eventModel */
            $eventModel = $eventsModule->createModel('Event');
            $event = $eventModel::findOne(['community_id' => $get['communityId']]);
            if ($event) {
                return $this->ruleLogic($user, $item, $params, $event);
            }
        }
        return false;
    }

    /**
     * Rule to Read Community
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        $communityUserMm = CommunityUserMm::find()->andWhere(['community_id' => $model->community_id,
            'role' => [Event::EVENTS_CHECK_IN, Event::EVENT_MANAGER]])
            ->andWhere(['user_id' => $user])->one();
        if (!empty($communityUserMm)) {
            return true;
        }
        return false;
    }
}
