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
use yii\rbac\Permission;

/**
 * Class m180108_112503_partnership_profiles_states_permissions_rule
 */
class m180108_112503_partnership_profiles_states_permissions_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\partnershipprofiles\rules\ReadAllExprOfIntRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for (read all) eoi',
                'ruleName' => \open20\amos\partnershipprofiles\rules\ReadAllExprOfIntRule::className(),
                'parent' => ['VALIDATED_BASIC_USER']
            ]
        ];
    }
}
