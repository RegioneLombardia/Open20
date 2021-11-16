<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\migrations
 * @category   CategoryName
 */

use open20\amos\comments\rules\DeleteOwnCommentReplyRule;
use open20\amos\comments\rules\DeleteOwnCommentRule;
use open20\amos\comments\rules\DeleteOwnContentCommentsRule;
use open20\amos\comments\rules\UpdateOwnCommentReplyRule;
use open20\amos\comments\rules\UpdateOwnCommentRule;
use open20\amos\comments\rules\UpdateOwnContentCommentsRule;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m170524_102950_init_comments_permissions
 */
class m170524_102950_init_comments_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setModelPermissions()
        );
    }
    
    private function setPluginRoles()
    {
        return [
            [
                'name' => 'COMMENTS_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator role for events plugin',
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'COMMENTS_CONTRIBUTOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Comments validator role for events plugin'
            ]
        ];
    }
    
    private function setModelPermissions()
    {
        return [
            
            // Rules permissions
            [
                'name' => UpdateOwnCommentRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to modify own comment',
                'ruleName' => UpdateOwnCommentRule::className(),
                'parent' => ['COMMENTS_CONTRIBUTOR']
            ],
            [
                'name' => DeleteOwnCommentRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to delete own comment',
                'ruleName' => DeleteOwnCommentRule::className(),
                'parent' => ['COMMENTS_CONTRIBUTOR']
            ],
            [
                'name' => UpdateOwnCommentReplyRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to modify own comment reply',
                'ruleName' => UpdateOwnCommentRule::className(),
                'parent' => ['COMMENTS_CONTRIBUTOR']
            ],
            [
                'name' => DeleteOwnCommentReplyRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to delete own comment reply',
                'ruleName' => DeleteOwnCommentRule::className(),
                'parent' => ['COMMENTS_CONTRIBUTOR']
            ],
            [
                'name' => UpdateOwnContentCommentsRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to modify all the comments on own contents',
                'ruleName' => UpdateOwnContentCommentsRule::className(),
                'parent' => ['COMMENTS_CONTRIBUTOR']
            ],
            [
                'name' => DeleteOwnContentCommentsRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to delete all the comments on own contents',
                'ruleName' => DeleteOwnContentCommentsRule::className(),
                'parent' => ['COMMENTS_CONTRIBUTOR']
            ],
            
            // Permissions for model Comment
            [
                'name' => 'COMMENT_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Create permission for model Comment',
                'parent' => ['COMMENTS_ADMINISTRATOR', 'COMMENTS_CONTRIBUTOR']
            ],
            [
                'name' => 'COMMENT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model Comment',
                'parent' => ['COMMENTS_ADMINISTRATOR', 'COMMENTS_CONTRIBUTOR']
            ],
            [
                'name' => 'COMMENT_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model Comment',
                'parent' => ['COMMENTS_ADMINISTRATOR', UpdateOwnCommentRule::className(), UpdateOwnContentCommentsRule::className()]
            ],
            [
                'name' => 'COMMENT_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission for model Comment',
                'parent' => ['COMMENTS_ADMINISTRATOR', DeleteOwnCommentRule::className(), DeleteOwnContentCommentsRule::className()]
            ],
            
            // Permissions for model CommentReply
            [
                'name' => 'COMMENTREPLY_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Create permission for model CommentReply',
                'parent' => ['COMMENTS_ADMINISTRATOR', 'COMMENTS_CONTRIBUTOR']
            ],
            [
                'name' => 'COMMENTREPLY_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model CommentReply',
                'parent' => ['COMMENTS_ADMINISTRATOR', 'COMMENTS_CONTRIBUTOR']
            ],
            [
                'name' => 'COMMENTREPLY_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model CommentReply',
                'parent' => ['COMMENTS_ADMINISTRATOR', UpdateOwnCommentReplyRule::className(), UpdateOwnContentCommentsRule::className()]
            ],
            [
                'name' => 'COMMENTREPLY_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission for model CommentReply',
                'parent' => ['COMMENTS_ADMINISTRATOR', DeleteOwnCommentReplyRule::className(), DeleteOwnContentCommentsRule::className()]
            ],
            
            // Permissions for model CommentContextAttribute
            [
                'name' => 'COMMENTCONTEXTATTRIBUTE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Create permission for model CommentContextAttribute',
                'parent' => ['COMMENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'COMMENTCONTEXTATTRIBUTE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model CommentContextAttribute',
                'parent' => ['COMMENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'COMMENTCONTEXTATTRIBUTE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model CommentContextAttribute',
                'parent' => ['COMMENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'COMMENTCONTEXTATTRIBUTE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission for model CommentContextAttribute',
                'parent' => ['COMMENTS_ADMINISTRATOR']
            ]
        ];
    }
}
