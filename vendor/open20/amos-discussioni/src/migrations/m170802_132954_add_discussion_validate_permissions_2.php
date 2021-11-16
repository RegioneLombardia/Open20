<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\discussioni\models\DiscussioniTopic;
use yii\rbac\Permission;

/**
 * Class m170802_132954_add_discussion_validate_permissions_2
 */
class m170802_132954_add_discussion_validate_permissions_2 extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DiscussionValidateOnDomain',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate at least one discussion in a domain with cwh permission',
                'ruleName' => \open20\amos\core\rules\UserValidatorContentRule::className(),
                'parent' => ['VALIDATORE_DISCUSSIONI', 'VALIDATED_BASIC_USER']
            ],
            [
                'name' => 'DiscussionValidate',
                'update' => true,
                'newValues' => [
                    'addParents' => ['VALIDATED_BASIC_USER']
                ]
            ],
            [
                'name' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicDaValidare::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['DiscussionValidateOnDomain']
                ]
            ],
            [
                'name' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_BOZZA,
                'update' => true,
                'newValues' => [
                    'addParents' => ['DiscussionValidate']
                ]
            ],
            [
                'name' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_DAVALIDARE,
                'update' => true,
                'newValues' => [
                    'addParents' => ['DiscussionValidate']
                ]
            ],
            [
                'name' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA,
                'update' => true,
                'newValues' => [
                    'addParents' => ['DiscussionValidate']
                ]
            ]
        ];
    }
}
