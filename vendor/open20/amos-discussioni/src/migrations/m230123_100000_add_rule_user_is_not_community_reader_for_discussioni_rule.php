<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\discussioni\rules\UserIsNotCommunityReaderDiscussioniRule;
use yii\rbac\Permission;

/**
 * Class m230123_100000_add_rule_user_is_not_community_reader_for_discussioni_rule
 */
class m230123_100000_add_rule_user_is_not_community_reader_for_discussioni_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => UserIsNotCommunityReaderDiscussioniRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola che controlla se un utente non ha il ruolo READER nelle community',
                'ruleName' => UserIsNotCommunityReaderDiscussioniRule::className(),
                'parent' => [
                    'CREATORE_DISCUSSIONI'
                ]
            ],
            [
                'name' => 'DISCUSSIONITOPIC_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model DiscussioniTopic',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        UserIsNotCommunityReaderDiscussioniRule::className()
                    ],
                    'removeParents' => [
                        'CREATORE_DISCUSSIONI'
                    ]
                ]
            ]
        ];
    }
}
