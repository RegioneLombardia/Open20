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
 * Class m181019_152500_add_ticket_faq_permissions
 */
class m181019_152500_add_ticket_faq_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return $this->setTicketFaqModelPermissions();
    }

    /**
     * Ticket categories model permissions
     *
     * @return array
     */
    private function setTicketFaqModelPermissions()
    {
        return [
            [
                'name' => 'TICKETFAQ_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model TicketFaq',
                'ruleName' => null,
                'parent' => ['REFERENTE_TICKET']
            ],
            [
                'name' => 'TICKETFAQ_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model TicketFaq',
                'ruleName' => null,
                'parent' => ['REFERENTE_TICKET']
            ],
            [
                'name' => 'TICKETFAQ_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model TicketFaq',
                'ruleName' => null,
                'parent' => ['REFERENTE_TICKET']
            ],
            [
                'name' => 'TICKETFAQ_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model TicketFaq',
                'ruleName' => null,
                'parent' => ['REFERENTE_TICKET']
            ]
        ];
    }
}
