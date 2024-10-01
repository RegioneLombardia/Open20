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

/**
 * Class m181119_103000_modify_ticket_and_ticket_categorie_permissions
 */
class m181119_103000_modify_ticket_and_ticket_categorie_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->updateTicketModelPermissions(),
            $this->updateTicketCategorieModelPermissions()
        );
    }

    /**
     * @return array
     */
    private function updateTicketModelPermissions()
    {
        return [
            [
                'name' => 'TICKET_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['AMMINISTRATORE_TICKET']
                ]
            ],
            [
                'name' => 'TICKET_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['OPERATORE_TICKET']
                ]
            ],
            [
                'name' => 'TICKET_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['OPERATORE_TICKET']
                ]
            ]
        ];
    }

    /**
     * Ticket categories model permissions
     *
     * @return array
     */
    private function updateTicketCategorieModelPermissions()
    {
        return [
            [
                'name' => 'TICKETCATEGORIE_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['REFERENTE_TICKET']
                ]
            ],
            [
                'name' => 'TICKETCATEGORIE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['OPERATORE_TICKET']
                ]
            ]
        ];
    }
}
