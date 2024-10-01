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
use open2\amos\ticket\rules\TicketCategoriaDeleteRule;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m181102_113700_modify_ticket_categorie_permissions
 */
class m181102_113700_modify_ticket_categorie_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setTicketCategorieRulePermissions(),
            $this->updateTicketCategorieModelPermissions()
        );
    }

    /**
     *
     * @return array
     */
    private function setTicketCategorieRulePermissions()
    {
        return [
            [
                'name' => TicketCategoriaDeleteRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Who can delete',
                'ruleName' => TicketCategoriaDeleteRule::className(),
                'parent' => ['REFERENTE_TICKET']
            ],
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
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model TicketCategorie',
                //'update' => true,
                'parent' => [TicketCategoriaDeleteRule::className()]
            ],
        ];
    }
}
