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

/**
 * Class m190308_162827_fix_referente_ticket_workflow_permissions
 */
class m190308_162827_fix_referente_ticket_workflow_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open2\amos\ticket\rules\workflow\TicketToClosedWorkflowRule::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['REFERENTE_TICKET']
                ]
            ]
        ];
    }
}
