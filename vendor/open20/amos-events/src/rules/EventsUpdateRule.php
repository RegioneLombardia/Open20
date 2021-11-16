<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\rules
 * @category   CategoryName
 */

namespace open20\amos\events\rules;

use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\record\Record;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventGroupReferent;
use open20\amos\events\models\EventGroupReferentMm;
use open20\amos\events\utility\EventsUtility;

/**
 * Class EventsUpdateRule
 * @package open20\amos\events\rules
 */
class EventsUpdateRule extends \open20\amos\core\rules\BasicContentRule
{
    public $name = 'EventsUpdate';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            /** @var Record $model */
            $model = $params['model'];

            /** @var AmosEvents $eventsModule */
            $eventsModule = AmosEvents::instance();
            if (is_null($eventsModule)) {
                return false;
            }

            if ($model::className() == $eventsModule->model('Event')) {

                if (!$model->id) {
                    $post = \Yii::$app->getRequest()->post();
                    $get = \Yii::$app->getRequest()->get();
                    if (isset($get['id'])) {
                        $model = $this->instanceModel($model, $get['id']);
                    } elseif (isset($post['id'])) {
                        $model = $this->instanceModel($model, $post['id']);
                    } elseif (array_key_exists('eid', $get)) {
                        /** @var Event $eventModel */
                        $eventModel = $eventsModule->createModel('Event');
                        $event = $eventModel::findOne(['id' => $get['eid']]);
                        if ($event) {
                            $model = $event;
                        }
                    } else {
                        return false;
                    }
                }
                $rulelogic = $this->ruleLogic($user, $item, $params, $model);
                $rulelogicOwnGroup = $this->ruleLogicOwnGroup($user, $item, $params, $model);
                return $rulelogic || $rulelogicOwnGroup;

            } elseif ($model::className() == Community::className()) {

                if (!$model->id) {
                    $post = \Yii::$app->getRequest()->post();
                    $get = \Yii::$app->getRequest()->get();
                    if (isset($get['id'])) {
                        $model = $this->instanceModel($model, $get['id']);
                    } elseif (isset($post['id'])) {
                        $model = $this->instanceModel($model, $post['id']);
                    } else {
                        return false;
                    }
                }

                $rulelogic = $this->ruleLogicForCommunity($user, $item, $params, $model);

                $rulelogicOwnGroup = $this->ruleLogicOwnGroup($user, $item, $params, $model);
                return $rulelogic || $rulelogicOwnGroup;

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
        $communityUserMm = CommunityUserMm::find()->andWhere(['community_id' => $model->community_id, 'role' => Event::EVENT_MANAGER])->andWhere(['user_id' => $user])->one();
        if (empty($communityUserMm)) {
            return false;
        }

        return true;
    }

    /**
     * Rule to Read Community
     * @inheritdoc
     */
    public function ruleLogicForCommunity($user, $item, $params, $model)
    {
        $communityUserMm = CommunityUserMm::find()->andWhere(['community_id' => $model->id, 'role' => Event::EVENT_MANAGER])->andWhere(['user_id' => $user])->one();
        if (empty($communityUserMm)) {
            return false;
        }
        return true;
    }

    /**
     * @param $user
     * @param $item
     * @param $params
     * @param $model Event
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function ruleLogicOwnGroup($user, $item, $params, $model)
    {
        return EventsUtility::isUserEventOperator($user, $model);
    }

}
