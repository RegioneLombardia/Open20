<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\rules\UpdateProfileFacilitatorRule;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170724_074724_associate_default_facilitator_rule_to_facilitator_role
 */
class m170724_074724_associate_default_facilitator_rule_to_facilitator_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => UpdateProfileFacilitatorRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for FACILITATOR role to update the profiles for which it is facilitator',
                'ruleName' => UpdateProfileFacilitatorRule::className(),
                'parent' => ['FACILITATOR']
            ],
            [
                'name' => 'USERPROFILE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['FACILITATOR']
                ]
            ],
            [
                'name' => 'USERPROFILE_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [UpdateProfileFacilitatorRule::className()]
                ]
            ]
        ];
    }
}
