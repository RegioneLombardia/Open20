<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\rules\DeleteOwnProfiloRule;
use open20\amos\organizzazioni\rules\UpdateOwnProfiloRule;
use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m210122_150607_fix_profilo_permissions
 */
class m210122_150607_fix_profilo_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            
            [
                'name' =>'PROFILO_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [UpdateOwnProfiloRule::className()]
                ]
            ],
            [
                'name' => 'PROFILO_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => [DeleteOwnProfiloRule::className()]
                ]
            ]
        ];
    }
}
