<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\organizzazioni\rules\UpdateLegalOperationalReferenceRule;

/**
 * Class m191208_211400_fix_manager_organizzazione_role
 */
class m191208_211400_fix_manager_organizzazione_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'BASIC_USER',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['MANAGER_ORGANIZZAZIONE']
                ]
            ],
            [
                'name' => 'PROFILO_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['MANAGER_ORGANIZZAZIONE']
                ]
            ],
            [
                'name' => 'PROFILOSEDI_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [UpdateLegalOperationalReferenceRule::className()]
                ]
            ],
        ];
    }
}
