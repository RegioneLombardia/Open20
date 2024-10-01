<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\events\models\Event;
use open20\amos\events\rules\AgidEventPublishRule;
use open20\amos\events\rules\AgidEventToPublishRule;
use open20\amos\events\rules\EventsUpdateRule;
use open20\amos\events\rules\workflow\EventsToValidateWorkflowRule;
use yii\rbac\Permission;

/**
 * Class m201115_235449_modify_agid_event_publication_permission
 */
class m201115_235449_modify_agid_event_publication_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => AgidEventPublishRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola pubblicazione eventi AGID',
                'ruleName' => AgidEventPublishRule::className(),
                'parent' => [
                    'EVENTS_CREATOR'
                ],
                'children' => [
                    Event::EVENTS_WORKFLOW_STATUS_PUBLISHED
                ]
            ],
            [
                'name' => AgidEventToPublishRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola richiesta pubblicazione eventi AGID',
                'ruleName' => AgidEventToPublishRule::className(),
                'parent' => [
                    EventsUpdateRule::className(),
                    EventsToValidateWorkflowRule::className()
                ],
                'children' => [
                    Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST
                ]
            ],
            [
                'name' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST,
                'update' => true,
                'newValues' => [
                    'removeParents' => [
                        EventsUpdateRule::className(),
                        EventsToValidateWorkflowRule::className()
                    ]
                ]
            ]
        ];
    }
}
