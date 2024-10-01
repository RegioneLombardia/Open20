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

/**
 * Class m190116_141125_modify_partnership_profiles_facilitator_permissions
 */
class m190116_141125_modify_partnership_profiles_facilitator_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'PARTNERSHIP_PROFILES_FACILITATOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR'],
                    'removeParents' => ['FACILITATOR']
                ]
            ]
        ];
    }
}
