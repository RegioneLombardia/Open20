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
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m181018_104417_add_ticket_permissions_roles
 */
class m181018_104417_add_ticket_permissions_roles extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setTicketCategorieModelPermissions(),
            $this->setWidgetsPermissions()
        );
    }

    /**
     * Plugin roles.
     *
     * @return array
     */
    private function setPluginRoles()
    {
        return [
            [
                'name' => 'OPERATORE_TICKET',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Operatore assistenza',
                'parent' => ['BASIC_USER', 'ADMIN']
            ],
            [
                'name' => 'REFERENTE_TICKET',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Referente assistenza',
                'parent' => ['AMMINISTRATORE_TICKET']
            ],
            [
                'name' => 'AMMINISTRATORE_TICKET',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Responsabile assistenza',
                'parent' => ['ADMIN']
            ],
        ];
    }

    /**
     * Ticket categories model permissions
     *
     * @return array
     */
    private function setTicketCategorieModelPermissions()
    {
        return [
            [
                'name' => 'TICKETCATEGORIE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model TicketCategorie',
                'ruleName' => null,
                'parent' => ['REFERENTE_TICKET']
            ],
            [
                'name' => 'TICKETCATEGORIE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model TicketCategorie',
                'ruleName' => null,
                'parent' => ['REFERENTE_TICKET']
            ],
            [
                'name' => 'TICKETCATEGORIE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model TicketCategorie',
                'ruleName' => null,
                'parent' => ['REFERENTE_TICKET']
            ],
            [
                'name' => 'TICKETCATEGORIE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model TicketCategorie',
                'ruleName' => null,
                'parent' => ['REFERENTE_TICKET']
            ]
        ];
    }

    /**
     * Plugin widgets permissions
     *
     * @return array
     */
    private function setWidgetsPermissions()
    {
        return [
            [
                'name' => open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconTicketDashboard',
                'ruleName' => null,
                'parent' => ['OPERATORE_TICKET']
            ],

        ];
    }
}
