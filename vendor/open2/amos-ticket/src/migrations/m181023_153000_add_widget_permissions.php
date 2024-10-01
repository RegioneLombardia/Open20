<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m181023_153000_add_widget_permissions
 */
class m181023_153000_add_widget_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketDashboard',
                'ruleName' => null,
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketFaq::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketFaq',
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketWaiting::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketWaiting',
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketProcessing::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketProcessing',
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketClosed::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketClosed',
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketAll::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketAll',
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketCategorie::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketCategorie',
                'parent' => ['REFERENTE_TICKET']
            ],
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketAdminFaq::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketAdminFaq',
                'parent' => ['REFERENTE_TICKET']
            ],
        ];
    }
}
