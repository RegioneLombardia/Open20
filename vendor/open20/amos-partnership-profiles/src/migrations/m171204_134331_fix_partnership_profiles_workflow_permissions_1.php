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
use open20\amos\partnershipprofiles\models\PartnershipProfiles;

/**
 * Class m171204_134331_fix_partnership_profiles_workflow_permissions_1
 */
class m171204_134331_fix_partnership_profiles_workflow_permissions_1 extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_FEEDBACKRECEIVED,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'PARTNERSHIP_PROFILES_READER',
                        'PARTNERSHIP_PROFILES_CREATOR',
                        'PARTNERSHIP_PROFILES_VALIDATOR',
                        'PARTNERSHIP_PROFILES_FACILITATOR',
                        'EXPRESSIONS_OF_INTEREST_READER',
                        'EXPRESSIONS_OF_INTEREST_CREATOR',
                        'PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR'
                    ]
                ]
            ],
            [
                'name' => PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_ARCHIVED,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'PARTNERSHIP_PROFILES_READER',
                        'PARTNERSHIP_PROFILES_CREATOR',
                        'PARTNERSHIP_PROFILES_VALIDATOR',
                        'PARTNERSHIP_PROFILES_FACILITATOR',
                        'EXPRESSIONS_OF_INTEREST_READER',
                        'EXPRESSIONS_OF_INTEREST_CREATOR',
                        'PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR'
                    ]
                ]
            ]
        ];
    }
}
