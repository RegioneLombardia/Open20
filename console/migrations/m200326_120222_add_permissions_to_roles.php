<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200323_141722_permissions
 */
class m200326_120222_add_permissions_to_roles extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'SUPER_USER',
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN'],
                ]
            ],
            [
                'name' => 'EVENTS_MANAGE_GROUP',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER'],
                ]
            ],
            [
                'name' => 'EVENTS_CREATOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER'],
                ]
            ],
            [
                'name' => 'AMMINISTRATORE_UTENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER'],
                ]
            ],
            [
                'name' => 'PRIVILEGES_MANAGER',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER'],
                ]
            ],
            [
                'name' => 'CHANGE_USERPROFILE_WORKFLOW_STATUS',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER'],
                ]
            ],
            [
                'name' => 'EVENTS_CREATOR',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['VALIDATED_BASIC_USER'],
                ]
            ],
            [
                'name' => \open20\amos\events\models\Event::EVENTS_WORKFLOW_STATUS_PUBLISHED,
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER'],
                ]
            ],

//---------------------------- Widgets ---------
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconManageAllEvents::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER'],
                ]
            ],
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconManageEvents::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER'],
                ]
            ],
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconCreateNewEvent::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER'],
                ]
            ],

            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEventDg::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER'],
                ]
            ],

            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEventOperators::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER'],
                ]
            ],
        ];
    }
}