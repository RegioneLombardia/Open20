<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m190924_085218_add_permission_comment_validate
 */
class m190924_085218_add_permission_comment_validate extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'CommentValidate',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate a comment with cwh query',
                 'ruleName' => \open20\amos\comments\rules\CommunityUpdateContentRule::className(),
                'parent' => ['VALIDATED_BASIC_USER']
            ],
            [
                'name' => 'COMMENT_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['CommentValidate']
                ]
            ],
            [
                'name' => 'COMMENTREPLY_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['CommentValidate']
                ]
            ],
            [
                'name' => 'COMMENT_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['CommentValidate']
                ]
            ],
            [
                'name' => 'COMMENTREPLY_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['CommentValidate']
                ]
            ],

        ];
    }
}
