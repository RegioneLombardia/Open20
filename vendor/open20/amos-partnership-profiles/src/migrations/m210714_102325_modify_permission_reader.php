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
class m210714_102325_modify_permission_reader extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'PARTNERSHIP_PROFILES_READER',
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER'],
                    'removeParents' => ['VALIDATED_BASIC_USER']
                ]
            ]   ,
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntDashboard::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER'],
                    'removeParents' => ['VALIDATED_BASIC_USER']
                ]
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER'],
                    'removeParents' => ['VALIDATED_BASIC_USER']
                ]
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestDashboard::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER'],
                    'removeParents' => ['VALIDATED_BASIC_USER']
                ]
            ]
        ];
    }
}
