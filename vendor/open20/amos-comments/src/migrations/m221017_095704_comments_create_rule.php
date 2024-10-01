<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\comments\rules\CreateCommentRule;
use yii\rbac\Permission;

/**
 * Class m221017_095704_comments_create_rule
 */
class m221017_095704_comments_create_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => CreateCommentRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to create comments if context is readable',
                'ruleName' => CreateCommentRule::className(),
                'parent' => ['COMMENTS_ADMINISTRATOR', 'COMMENTS_CONTRIBUTOR']
            ],
            [
                'name' => 'COMMENT_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [CreateCommentRule::className()],
                    'removeParents' => ['COMMENTS_CONTRIBUTOR']
                ]
            ],
            [
                'name' => 'COMMENTREPLY_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [CreateCommentRule::className()],
                    'removeParents' => ['COMMENTS_CONTRIBUTOR']
                ]
            ],
            [
                'name' => 'COMMENT_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => [CreateCommentRule::className()],
                    'removeParents' => ['COMMENTS_CONTRIBUTOR']
                ]
            ],
            [
                'name' => 'COMMENTREPLY_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => [CreateCommentRule::className()],
                    'removeParents' => ['COMMENTS_CONTRIBUTOR']
                ]
            ]
        ];
    }
}
