<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200316_153401_event_landing_permissions*/
class m200316_153401_event_landing_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'EVENT_LANDING_MANAGER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Permesso di gestire la landing',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'EVENTLANDING_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model EventLanding',
                'ruleName' => null,
                'parent' => ['EVENT_LANDING_MANAGER']
            ],
            [
                'name' => 'EVENTLANDING_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model EventLanding',
                'ruleName' => null,
                'parent' => ['EVENT_LANDING_MANAGER']
            ],
            [
                'name' => 'EVENTLANDING_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model EventLanding',
                'ruleName' => null,
                'parent' => ['EVENT_LANDING_MANAGER']
            ],
            [
                'name' => 'EVENTLANDING_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model EventLanding',
                'ruleName' => null,
                'parent' => ['EVENT_LANDING_MANAGER']
            ],

            [
                'name' => 'EVENTLANDINGPROTAGONIST_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model EventLandingProtagonist',
                'ruleName' => null,
                'parent' => ['EVENT_LANDING_MANAGER']
            ],
            [
                'name' => 'EVENTLANDINGPROTAGONIST_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model EventLandingProtagonist',
                'ruleName' => null,
                'parent' => ['EVENT_LANDING_MANAGER']
            ],
            [
                'name' => 'EVENTLANDINGPROTAGONIST_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model EventLandingProtagonist',
                'ruleName' => null,
                'parent' => ['EVENT_LANDING_MANAGER']
            ],
            [
                'name' => 'EVENTLANDINGPROTAGONIST_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model EventLandingProtagonist',
                'ruleName' => null,
                'parent' => ['EVENT_LANDING_MANAGER']
            ],


            [
                'name' => 'EVENTLANDINGNEWS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model EventLandingNews',
                'ruleName' => null,
                'parent' => ['EVENT_LANDING_MANAGER']
            ],
            [
                'name' => 'EVENTLANDINGNEWS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model EventLandingNews',
                'ruleName' => null,
                'parent' => ['EVENT_LANDING_MANAGER']
            ],
            [
                'name' => 'EVENTLANDINGNEWS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model EventLandingNews',
                'ruleName' => null,
                'parent' => ['EVENT_LANDING_MANAGER']
            ],
            [
                'name' => 'EVENTLANDINGNEWS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model EventLandingNews',
                'ruleName' => null,
                'parent' => ['EVENT_LANDING_MANAGER']
            ],

        ];
    }
}
