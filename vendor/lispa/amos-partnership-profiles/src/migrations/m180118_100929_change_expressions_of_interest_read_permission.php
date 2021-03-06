<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\partnershipprofiles\migrations
 * @category   CategoryName
 */

use lispa\amos\core\migration\AmosMigrationPermissions;
use lispa\amos\partnershipprofiles\rules\ReadOwnExprOfIntRule;
use yii\rbac\Permission;

/**
 * Class m180118_100929_change_expressions_of_interest_read_permission
 */
class m180118_100929_change_expressions_of_interest_read_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => ReadOwnExprOfIntRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to draft a partnership profile',
                'ruleName' => ReadOwnExprOfIntRule::className(),
                'parent' => ['EXPRESSIONS_OF_INTEREST_CREATOR', 'EXPRESSIONS_OF_INTEREST_READER'],
                'children' => ['EXPRESSIONSOFINTEREST_READ']
            ],
            [
                'name' => 'EXPRESSIONSOFINTEREST_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['EXPRESSIONS_OF_INTEREST_CREATOR', 'EXPRESSIONS_OF_INTEREST_READER']
                ]
            ]
        ];
    }
}
