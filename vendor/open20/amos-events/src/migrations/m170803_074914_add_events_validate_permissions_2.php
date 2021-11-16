<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use open20\amos\events\models\Event;

/**
 * Class m170803_074914_add_events_validate_permissions_2
 */
class m170803_074914_add_events_validate_permissions_2 extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'EventValidateOnDomain',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate at least one event in a domain with cwh permission',
                'ruleName' => \open20\amos\core\rules\UserValidatorContentRule::className(),
                'parent' => ['PLATFORM_EVENTS_VALIDATOR', 'EVENTS_VALIDATOR', 'VALIDATED_BASIC_USER']
            ],
            [
                'name' => 'EventValidate',
                'update' => true,
                'newValues' => [
                    'addParents' => ['VALIDATED_BASIC_USER']
                ]
            ],
            [
                'name' => open20\amos\events\widgets\icons\WidgetIconEventsToPublish::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['EventValidateOnDomain']
                ]
            ],
            [
                'name' => Event::EVENTS_WORKFLOW_STATUS_DRAFT,
                'update' => true,
                'newValues' => [
                    'addParents' => ['EventValidate']
                ]
            ],
            [
                'name' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST,
                'update' => true,
                'newValues' => [
                    'addParents' => ['EventValidate']
                ]
            ],
            [
                'name' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED,
                'update' => true,
                'newValues' => [
                    'addParents' => ['EventValidate']
                ]
            ]
        ];
    }
}

?>
