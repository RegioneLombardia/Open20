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
use open20\amos\partnershipprofiles\rules\DeleteFacilitatorOwnExprOfIntRule;
use open20\amos\partnershipprofiles\rules\DeleteFacilitatorOwnPartnershipProfilesRule;
use open20\amos\partnershipprofiles\rules\UpdateFacilitatorOwnExprOfIntRule;
use open20\amos\partnershipprofiles\rules\UpdateFacilitatorOwnPartnershipProfilesRule;

/**
 * Class m180109_102840_fix_partnership_profiles_facilitator_permissions
 */
class m180109_102840_fix_partnership_profiles_facilitator_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'PARTNERSHIPPROFILES_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [UpdateFacilitatorOwnPartnershipProfilesRule::className()]
                ]
            ],
            [
                'name' => 'PARTNERSHIPPROFILES_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => [DeleteFacilitatorOwnPartnershipProfilesRule::className()]
                ]
            ],
            [
                'name' => 'EXPRESSIONSOFINTEREST_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [UpdateFacilitatorOwnExprOfIntRule::className()]
                ]
            ],
            [
                'name' => 'EXPRESSIONSOFINTEREST_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => [DeleteFacilitatorOwnExprOfIntRule::className()]
                ]
            ]
        ];
    }
}
