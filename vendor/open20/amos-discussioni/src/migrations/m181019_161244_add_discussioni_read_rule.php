<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m180727_124144_add_news_read_rule
 */
class m181019_161244_add_discussioni_read_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DscussionRead',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to read a News ',
                'ruleName' => \open20\amos\core\rules\ReadContentRule::className(),
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', 'CREATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', 'LETTORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DISCUSSIONITOPIC_READ',
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'removeParents' =>  ['AMMINISTRATORE_DISCUSSIONI', 'CREATORE_DISCUSSIONI', 'VALIDATORE_DISCUSSIONI', 'LETTORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI'],
                    'addParents' => ['DscussionRead']
                ]
            ],
        ];
    }
}
