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
class m181019_164144_add_events_read_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'EventsRead',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to read a News ',
                'ruleName' => \open20\amos\core\rules\ReadContentRule::className(),
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR', 'EVENTS_VALIDATOR', 'EVENTS_READER', 'PLATFORM_EVENTS_VALIDATOR', 'EVENTS_MANAGER']
            ],
            [
                'name' => 'EVENT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR', 'EVENTS_VALIDATOR', 'EVENTS_READER', 'PLATFORM_EVENTS_VALIDATOR', 'EVENTS_MANAGER'],
                    'addParents' => ['EventsRead']
                ]
            ],
        ];
    }
}
