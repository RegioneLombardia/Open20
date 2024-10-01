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
use open20\amos\partnershipprofiles\rules\PartnershipProfilesDraftStatusRule;
use yii\rbac\Permission;

/**
 * Class m180110_160844_change_partnership_profiles_states_permissions_2
 */
class m180110_160844_change_partnership_profiles_states_permissions_2 extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => PartnershipProfilesDraftStatusRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to draft a partnership profile',
                'ruleName' => PartnershipProfilesDraftStatusRule::className(),
                'parent' => ['PARTNERSHIP_PROFILES_CREATOR', 'PARTNERSHIP_PROFILES_FACILITATOR'],
                'children' => [PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT]
            ],
            [
                'name' => PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['PARTNERSHIP_PROFILES_CREATOR']
                ]
            ]
        ];
    }
}
