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
use open20\amos\core\record\Record;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventAccreditationList;

/**
 * Class EventsAccreditationListCRUDRule
 * @package open20\amos\events\rules
 */
class EventsAccreditationListCRUDRule extends \open20\amos\core\rules\BasicContentRule
{
    public $name = 'EventsAccreditationListCRUDRule';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            /** @var Record $model */
            $model = $params['model'];
            if (!$model->id) {
                $post = \Yii::$app->getRequest()->post();
                $get = \Yii::$app->getRequest()->get();
                if (isset($get['id'])) {
                    $model = $this->instanceModel($model, $get['id']);
                } elseif (isset($post['id'])) {
                    $model = $this->instanceModel($model, $post['id']);
                } elseif (array_key_exists('eid', $get)) {
                    /** @var Event $eventModel */
                    $eventModel = AmosEvents::instance()->createModel('Event');
                    $model = $eventModel::findOne(['id' => $get['eid']]);
                }
            }
            return $this->ruleLogic($user, $item, $params, $model);
        }

        return false;
    }

    /**
     * Rule to Read Community
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        if ($model instanceof Event) {
            $communityUserMm = CommunityUserMm::find()->andWhere(['community_id' => $model->community_id, 'role' => Event::EVENT_MANAGER])->andWhere(['user_id' => $user])->one();
            if (!empty($communityUserMm)) {
                return true;
            }
        } elseif ($model instanceof EventAccreditationList) {
            $communityUserMm = CommunityUserMm::find()->andWhere(['community_id' => $model->event_id, 'role' => Event::EVENT_MANAGER])->andWhere(['user_id' => $user])->one();
            if (!empty($communityUserMm)) {
                return true;
            }
        }

        return false;
    }
}
