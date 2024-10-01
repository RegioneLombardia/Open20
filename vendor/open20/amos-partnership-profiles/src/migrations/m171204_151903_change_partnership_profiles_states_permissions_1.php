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
use open20\amos\partnershipprofiles\rules\PartnershipProfilesCloseStatusRule;
use yii\rbac\Permission;

/**
 * Class m171204_151903_change_partnership_profiles_states_permissions_1
 */
class m171204_151903_change_partnership_profiles_states_permissions_1 extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => PartnershipProfilesCloseStatusRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to close a partnership profile',
                'ruleName' => PartnershipProfilesCloseStatusRule::className(),
                'parent' => ['PARTNERSHIP_PROFILES_CREATOR', 'PARTNERSHIP_PROFILES_FACILITATOR'],
                'children' => [PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_CLOSED]
            ],
            [
                'name' => PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_CLOSED,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['PARTNERSHIP_PROFILES_CREATOR']
                ]
            ]
        ];
    }
}
