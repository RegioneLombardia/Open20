<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\rules\DeactivateAccountRule;
use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m180521_140827_modify_admin_permission_deactivate_account
 */
class m180521_140827_modify_admin_permission_deactivate_account extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DeactivateAccount',
                'update' => true,
                'newValues' => [
                    'ruleName' => DeactivateAccountRule::className()
                ],
                'oldValues' => [
                    'ruleName' => null
                ]
            ]
        ];
    }
}
