<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\documenti\rules\UserIsNotCommunityReaderDocumentiRule;
use yii\rbac\Permission;

/**
 * Class m230123_100000_add_rule_user_is_not_community_reader_for_documenti_rule
 */
class m230123_100000_add_rule_user_is_not_community_reader_for_documenti_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => UserIsNotCommunityReaderDocumentiRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola che controlla se un utente non ha il ruolo READER',
                'ruleName' => UserIsNotCommunityReaderDocumentiRule::className(),
                'parent' => [
                    'CREATORE_DOCUMENTI'
                ]
            ],
            [
                'name' => 'DOCUMENTI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Documenti',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        UserIsNotCommunityReaderDocumentiRule::className()
                    ],
                    'removeParents' => [
                        'CREATORE_DOCUMENTI'
                    ]
                ]
            ]
        ];
    }
}
