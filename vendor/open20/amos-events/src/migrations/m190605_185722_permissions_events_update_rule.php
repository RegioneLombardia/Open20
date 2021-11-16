<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m190605_185722_permissions_events_update_rule
 */
class m190605_185722_permissions_events_update_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\events\rules\EventsUpdateRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di effettuare modifiche negli eventi in cui il partecipante è events_manager',
                'ruleName' => \open20\amos\events\rules\EventsUpdateRule::className(),
                'children' => [
                    'EVENT_READ',
                    'EVENT_UPDATE',
                    'COMMUNITY_READ',
                    'COMMUNITY_UPDATE',
                    'EventWorkflow/DRAFT',
                    'EventWorkflow/PUBLISHREQUEST',
                ],
                'parent' => ['EVENTS_READER'],
            ],
        ];
    }
}
