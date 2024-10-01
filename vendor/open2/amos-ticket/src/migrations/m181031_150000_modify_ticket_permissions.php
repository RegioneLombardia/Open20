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
use open2\amos\ticket\rules\TicketReadRule;
use open2\amos\ticket\rules\TicketUpdateRule;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m181031_150000_modify_ticket_permissions
 */
class m181031_150000_modify_ticket_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setTicketRulePermissions(),
            $this->updateTicketModelPermissions()
        );
    }

    /**
     * Workflow statuses permissions
     *
     * @return array
     */
    private function setTicketRulePermissions()
    {
        return [
            [
                'name' => TicketReadRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Who can view',
                'ruleName' => TicketReadRule::className(),
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => TicketUpdateRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Owner and referent can update',
                'ruleName' => TicketUpdateRule::className(),
                'parent' => ['OPERATORE_TICKET']
            ],
        ];
    }

    /**
     * Ticket categories model permissions
     *
     * @return array
     */
    private function updateTicketModelPermissions()
    {
        return [
            [
                'name' => 'TICKET_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => [TicketReadRule::className()]
                ]
            ],
            [
                'name' => 'TICKET_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [TicketUpdateRule::className()]
                ]
            ]
        ];
    }
}
