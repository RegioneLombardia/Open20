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
 * Class m191003_121523_event_seats_permissions*/
class m200326_100523_event_permissions_general extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'EVENT_DG_OPERATOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG'],
                ]
            ],
            [
                'name' => 'EVENTS_MANAGE_SECONDARY_TABLES',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Manage secondary tables',
                'children' => [
                    'EVENTLOCATION_READ','EVENTLOCATION_CREATE','EVENTLOCATION_UPDATE','EVENTLOCATION_DELETE','EVENTLOCATION_UPDATE',
                    'EVENTINTERNALLIST_READ','EVENTINTERNALLIST_CREATE','EVENTINTERNALLIST_UPDATE','EVENTINTERNALLIST_DELETE','EVENTINTERNALLIST_UPDATE',
                    'EVENTSEATS_READ','EVENTSEATS_CREATE','EVENTSEATS_UPDATE','EVENTSEATS_DELETE','EVENTSEATS_UPDATE',
                    'EVENTACCREDITATIONLIST_READ','EVENTACCREDITATIONLIST_CREATE','EVENTACCREDITATIONLIST_UPDATE','EVENTACCREDITATIONLIST_DELETE','EVENTACCREDITATIONLIST_UPDATE',
                ],
                'parent' => ['EVENTS_ADMINISTRATOR','EVENTS_CREATOR']
            ],       
            [
                'name' => 'EVENTS_MANAGE_GROUP',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Manage secondary tables',
                'children' => [
                    'EVENTGROUPREFERENT_READ','EVENTGROUPREFERENT_CREATE','EVENTGROUPREFERENT_UPDATE','EVENTGROUPREFERENT_DELETE','EVENTGROUPREFERENT_UPDATE',
                    'EVENTGROUPREFERENTMM_READ','EVENTGROUPREFERENTMM_CREATE','EVENTGROUPREFERENTMM_UPDATE','EVENTGROUPREFERENTMM_DELETE','EVENTGROUPREFERENTMM_UPDATE',
                ],
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'EVENTS_CONFIGURATIONS',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Manage events configurations',
                'children' => [
                    'EVENTEMAILTEMPLATES_READ', 'EVENTEMAILTEMPLATES_CREATE','EVENTEMAILTEMPLATES_UPDATE','EVENTEMAILTEMPLATES_DELETE','EVENTEMAILTEMPLATES_UPDATE',
                    'EVENTLOCATIONENTRANCES_READ','EVENTLOCATIONENTRANCES_CREATE','EVENTLOCATIONENTRANCES_UPDATE','EVENTLOCATIONENTRANCES_DELETE','EVENTLOCATIONENTRANCES_UPDATE',
                    'EVENTTYPE_READ', 'EVENTTYPE_CREATE','EVENTTYPE_UPDATE','EVENTTYPE_DELETE','EVENTTYPE_UPDATE',
                    'EVENTCATEGORY_READ','EVENTCATEGORY_CREATE','EVENTCATEGORY_UPDATE','EVENTCATEGORY_DELETE','EVENTCATEGORY_UPDATE',
                ],
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'EVENTS_CREATOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG_OPERATOR'],
                ]
            ],
            [
                'name' => 'EVENT_LANDING_MANAGER',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENTS_CREATOR'],
                ]
            ],

            [
                'name' =>  'EVENTGROUPREFERENTMM_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG'],
                ]
            ],
            [
                'name' =>  'EVENTGROUPREFERENTMM_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG'],
                ]
            ],
            [
                'name' =>  'EVENTGROUPREFERENTMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG'],
                ]
            ],
            [
                'name' =>  'EVENTGROUPREFERENTMM_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG'],
                ]
            ],

//-------------------------------------------- WORKFLOW
            [
                'name' => \open20\amos\events\models\Event::EVENTS_WORKFLOW_STATUS_PUBLISHED,
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG_OPERATOR'],
                ]
            ],
//----------------------------------------------------------------- WIDGETS ----------

            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconManageAllEvents::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENTS_ADMINISTRATOR'],
                ]
            ],
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconManageEvents::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG'],
                ]
            ],
//-----------------------------------------------------------------
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconCreateNewEvent::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Manage events',
                'parent' => ['EVENT_DG_OPERATOR','EVENTS_ADMINISTRATOR']
            ],

            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEventDg::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Gestisci Dg',
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],

            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEventOperators::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Operators',
                'parent' => ['EVENTS_ADMINISTRATOR','EVENT_DG']
            ],
        ];
    }
}
