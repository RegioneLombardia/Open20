<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\news\rules\UserIsNotCommunityReaderNewsRule;
use yii\rbac\Permission;

/**
 * Class m230120_123600_add_rule_user_is_not_community_reader_for_news_rule
 */
class m230120_123600_add_rule_user_is_not_community_reader_for_news_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => UserIsNotCommunityReaderNewsRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola che controlla se un utente non ha il ruolo READER',
                'ruleName' => UserIsNotCommunityReaderNewsRule::className(),
                'parent' => [
                    'CREATORE_NEWS'
                ]
            ],
            [
                'name' => 'NEWS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model News',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        UserIsNotCommunityReaderNewsRule::className()
                    ],
                    'removeParents' => [
                        'CREATORE_NEWS'
                    ]
                ]
            ]
        ];
    }
}
