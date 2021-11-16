<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 28/05/2020
 * Time: 15:19
 */

namespace open20\amos\events\events;


use open20\amos\events\models\EventGroupReferentMm;
use yii\base\Event;
use yii\base\Exception;

class AssociateGroupMmEvent
{

    public function associateRoleCandidatoOperator(Event $event)
    {
        /** @var  $eventGroupReferentMm EventGroupReferentMm */
        $eventGroupReferentMm = $event->data;
        $moduleMoodle = \Yii::$app->getModule('moodle');
        $moduleEvents = \Yii::$app->getModule('events');
        if ($moduleMoodle && $moduleEvents && !empty($moduleEvents->operatorCandidate['enabled'])) {
            if ($eventGroupReferentMm && $eventGroupReferentMm->exclude_from_query == 1 && $moduleEvents->operatorCandidate['role']) {
                $role = \Yii::$app->authManager->getRole($moduleEvents->operatorCandidate['role']);
                if ($role) {
                    if (!\Yii::$app->authManager->checkAccess($eventGroupReferentMm->user_id, $moduleEvents->operatorCandidate['role'])) {
                        \Yii::$app->authManager->assign($role, $eventGroupReferentMm->user_id);
                        \Yii::$app->authManager->deleteAllCache();
                    }
                    try {
                        $eventRoleUse = new \open20\amos\moodle\bootstrap\EventRoleUser();
                        $eventRoleUse->enableMoodleUser($eventGroupReferentMm->user_id, 'MOODLE_STUDENT');
                    } catch (Exception $e) {
                    }
                }
            }
        }
    }
}