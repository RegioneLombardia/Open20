<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open2\amos\ticket\widgets\icons\WidgetIconTicketAdminFaq;
use open2\amos\ticket\widgets\icons\WidgetIconTicketCategorie;
use yii\rbac\Permission;

/**
 * Class m190207_115303_add_widget_permissions_to_ticket_manager_in_community_permission
 */
class m190207_115303_add_widget_permissions_to_ticket_manager_in_community_permission extends \open20\amos\core\migration\AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => WidgetIconTicketCategorie::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketCategorie',
                'parent' => ['TICKET_MANAGER_FOR_COMMUNITY']
            ],
            [
                'name' => WidgetIconTicketAdminFaq::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketAdminFaq',
                'parent' => ['TICKET_MANAGER_FOR_COMMUNITY']
            ]
        ];
    }
}
