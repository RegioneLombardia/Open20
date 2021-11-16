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
 * Class m190605_201022_permissions_events_accreditation_list_crud_rule
 */
class m190605_201022_permissions_events_accreditation_list_crud_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'EVENTACCREDITATIONLIST_MANAGER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Manager for events event accreditation lists',
                'ruleName' => null,
                'parent' => ['EVENTS_READER']
            ],
            [
                'name' => \open20\amos\events\rules\EventsAccreditationListCRUDRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di effettuare operazioni crud nelle liste accreditamento di eventi in cui il partecipante è events_manager',
                'ruleName' => \open20\amos\events\rules\EventsAccreditationListCRUDRule::className(),
                'children' => [
                    'EVENTACCREDITATIONLIST_CREATE',
                    'EVENTACCREDITATIONLIST_READ',
                    'EVENTACCREDITATIONLIST_UPDATE',
                    'EVENTACCREDITATIONLIST_DELETE',
                ],
                'parent' => ['EVENTACCREDITATIONLIST_MANAGER'],
            ],
        ];
    }
}
