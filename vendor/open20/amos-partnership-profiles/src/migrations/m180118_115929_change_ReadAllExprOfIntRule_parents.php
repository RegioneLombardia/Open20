<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\partnershipprofiles\rules\ReadAllExprOfIntRule;

/**
 * Class m180118_115929_change_ReadAllExprOfIntRule_parents
 */
class m180118_115929_change_ReadAllExprOfIntRule_parents extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => ReadAllExprOfIntRule::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_READER'],
                    'removeParents' => ['VALIDATED_BASIC_USER']
                ]
            ]
        ];
    }
}
