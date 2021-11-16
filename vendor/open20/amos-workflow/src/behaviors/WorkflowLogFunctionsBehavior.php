<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\workflow\behaviors
 * @category   CategoryName
 */

namespace open20\amos\workflow\behaviors;

use open20\amos\core\record\Record;
use open20\amos\workflow\components\events\SimpleWorkFlowEventsListener;
use open20\amos\workflow\models\WorkflowTransitionsLog;
use yii\base\Behavior;
use yii\helpers\Json;

/**
 * Class WorkflowLogFunctionsBehavior
 * @package open20\amos\workflow\behaviors
 */
class WorkflowLogFunctionsBehavior extends Behavior
{

    /**
     * @var string $changeStatusComment - attribute for model to send comment data on status change
     */
    public $changeStatusComment;

    public function getStatusLastUpdateTime($status)
    {
        /** @var Record $owner */
        $owner = $this->owner;
        return SimpleWorkFlowEventsListener::getLastUpdateTime($owner->className(), $owner->primaryKey, $status);
    }

    public function getStatusLastUpdateUser($status)
    {
        /** @var Record $owner */
        $owner = $this->owner;
        return SimpleWorkFlowEventsListener::getLastUpdateUser($owner->className(), $owner->primaryKey, $status);
    }

    public function getStatusFirstUpdateUser($status)
    {
        /** @var Record $owner */
        $owner = $this->owner;
        return SimpleWorkFlowEventsListener::getFirstUpdateUser($owner->className(), $owner->primaryKey, $status);
    }

    /**
     * @param $status string, the status of SimpleWorkflowBehavior
     * @return null|number (user)
     */
    public function getStatusLastUpdateComment($status)
    {
        /** @var Record $owner */
        $owner = $this->owner;
        $class = $owner->className();
        $primaryKey = $owner->primaryKey;
        $primaryKeyJson = Json::encode($primaryKey);
        $logList = WorkflowTransitionsLog::find()->andWhere([
            'classname' => $class,
            'owner_primary_key' => $primaryKeyJson,
            'end_status' => $status
        ])->orderBy('created_at DESC')->all();
        if (count($logList) >= 1) {
            /** @var WorkflowTransitionsLog $wtl */
            $wtl = reset($logList);
            return isset($wtl->comment) ? $wtl->comment : null;
        }
        return null;
    }

    public function getStatusUpdateHistory()
    {
        /** @var Record $owner */
        $owner = $this->owner;
        return SimpleWorkFlowEventsListener::getUpdateHistory($owner->className(), $owner->primaryKey);
    }
}
