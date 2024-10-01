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
use open20\amos\core\rules\UserValidatorContentRule;
use open20\amos\core\rules\ValidatorUpdateContentRule;
use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use open20\amos\partnershipprofiles\rules\DeleteFacilitatorOwnExprOfIntRule;
use open20\amos\partnershipprofiles\rules\DeleteFacilitatorOwnPartnershipProfilesRule;
use open20\amos\partnershipprofiles\rules\DeleteOwnExprOfIntRule;
use open20\amos\partnershipprofiles\rules\DeleteOwnPartnershipProfilesRule;
use open20\amos\partnershipprofiles\rules\UpdateFacilitatorOwnExprOfIntRule;
use open20\amos\partnershipprofiles\rules\UpdateFacilitatorOwnPartnershipProfilesRule;
use open20\amos\partnershipprofiles\rules\UpdateOwnExprOfIntRule;
use open20\amos\partnershipprofiles\rules\UpdateOwnPartnershipProfilesRule;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m170913_102226_init_partnership_profiles_permissions
 */
class m170913_102226_init_partnership_profiles_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setModelPermissions(),
            $this->setWidgetsPermissions(),
            $this->setWorkflowPermissions()
        );
    }

    private function setPluginRoles()
    {
        return [

            // Entire plugin administrator
            [
                'name' => 'PARTNERSHIP_PROFILES_PLUGIN_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator role for the entire plugin',
                'parent' => ['ADMIN']
            ],

            // Partnership profiles roles
            [
                'name' => 'PARTNERSHIP_PROFILES_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator role for partnership profiles',
                'parent' => ['PARTNERSHIP_PROFILES_PLUGIN_ADMINISTRATOR']
            ],
            [
                'name' => 'PARTNERSHIP_PROFILES_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Reader role for partnership profiles',
                'parent' => ['PARTNERSHIP_PROFILES_PLUGIN_ADMINISTRATOR']
            ],
            [
                'name' => 'PARTNERSHIP_PROFILES_CREATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Creator role for partnership profiles',
                'parent' => ['PARTNERSHIP_PROFILES_PLUGIN_ADMINISTRATOR']
            ],
            [
                'name' => 'PARTNERSHIP_PROFILES_VALIDATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Validator role for partnership profiles',
                'parent' => ['PARTNERSHIP_PROFILES_PLUGIN_ADMINISTRATOR', 'VALIDATOR']
            ],
            [
                'name' => 'PARTNERSHIP_PROFILES_FACILITATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Validator role for partnership profiles',
                'parent' => ['PARTNERSHIP_PROFILES_PLUGIN_ADMINISTRATOR', 'FACILITATOR']
            ],

            // Expressions of interest roles
            [
                'name' => 'EXPRESSIONS_OF_INTEREST_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator role for expressions of interest',
                'parent' => ['PARTNERSHIP_PROFILES_PLUGIN_ADMINISTRATOR']
            ],
            [
                'name' => 'EXPRESSIONS_OF_INTEREST_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Reader role for expressions of interest',
                'parent' => ['PARTNERSHIP_PROFILES_PLUGIN_ADMINISTRATOR']
            ],
            [
                'name' => 'EXPRESSIONS_OF_INTEREST_CREATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Creator role for expressions of interest',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR']
            ],

            // Partner. prof. and expr. of int. role for facilitator
            [
                'name' => 'PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator role for partner. prof. and expr. of int. for facilitator',
                'parent' => ['PARTNERSHIP_PROFILES_PLUGIN_ADMINISTRATOR', 'FACILITATOR']
            ]
        ];
    }

    private function setModelPermissions()
    {
        return [

            // Rules permissions
            [
                'name' => UpdateOwnPartnershipProfilesRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to modify own partnership profiles',
                'ruleName' => UpdateOwnPartnershipProfilesRule::className(),
                'parent' => ['PARTNERSHIP_PROFILES_CREATOR']
            ],
            [
                'name' => DeleteOwnPartnershipProfilesRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to delete own partnership profiles',
                'ruleName' => DeleteOwnPartnershipProfilesRule::className(),
                'parent' => ['PARTNERSHIP_PROFILES_CREATOR']
            ],
            [
                'name' => UpdateFacilitatorOwnPartnershipProfilesRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for facilitator to modify own partnership profiles',
                'ruleName' => UpdateFacilitatorOwnPartnershipProfilesRule::className(),
                'parent' => ['PARTNERSHIP_PROFILES_FACILITATOR']
            ],
            [
                'name' => DeleteFacilitatorOwnPartnershipProfilesRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for facilitator to delete own partnership profiles',
                'ruleName' => DeleteFacilitatorOwnPartnershipProfilesRule::className(),
                'parent' => ['PARTNERSHIP_PROFILES_FACILITATOR']
            ],
            [
                'name' => UpdateOwnExprOfIntRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to modify own expression of interest',
                'ruleName' => UpdateOwnExprOfIntRule::className(),
                'parent' => ['EXPRESSIONS_OF_INTEREST_CREATOR']
            ],
            [
                'name' => DeleteOwnExprOfIntRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to delete own expression of interest',
                'ruleName' => DeleteOwnExprOfIntRule::className(),
                'parent' => ['EXPRESSIONS_OF_INTEREST_CREATOR']
            ],
            [
                'name' => UpdateFacilitatorOwnExprOfIntRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for facilitator to modify own expressions of interest',
                'ruleName' => UpdateFacilitatorOwnExprOfIntRule::className(),
                'parent' => ['PARTNERSHIP_PROFILES_FACILITATOR']
            ],
            [
                'name' => DeleteFacilitatorOwnExprOfIntRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for facilitator to delete own expressions of interest',
                'ruleName' => DeleteFacilitatorOwnExprOfIntRule::className(),
                'parent' => ['PARTNERSHIP_PROFILES_FACILITATOR']
            ],
            [
                'name' => 'PartnershipProfilesValidate',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate a PartnershipProfiles with cwh query',
                'ruleName' => ValidatorUpdateContentRule::className(),
                'parent' => ['PARTNERSHIP_PROFILES_VALIDATOR', 'VALIDATED_BASIC_USER']
            ],
            [
                'name' => 'PartnershipProfilesValidateOnDomain',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate at least one PartnershipProfiles in a domain with cwh permission',
                'ruleName' => UserValidatorContentRule::className(),
                'parent' => ['PARTNERSHIP_PROFILES_VALIDATOR', 'VALIDATED_BASIC_USER']
            ],

            // Permissions for model PartnershipProfiles
            [
                'name' => 'PARTNERSHIPPROFILES_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Create permission for model PartnershipProfiles',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_CREATOR']
            ],
            [
                'name' => 'PARTNERSHIPPROFILES_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model PartnershipProfiles',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_CREATOR', 'PARTNERSHIP_PROFILES_READER']
            ],
            [
                'name' => 'PARTNERSHIPPROFILES_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model PartnershipProfiles',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', UpdateOwnPartnershipProfilesRule::className()]
            ],
            [
                'name' => 'PARTNERSHIPPROFILES_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission for model PartnershipProfiles',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', DeleteOwnPartnershipProfilesRule::className()]
            ],

            // Permissions for model ExpressionsOfInterest
            [
                'name' => 'EXPRESSIONSOFINTEREST_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Create permission for model ExpressionsOfInterest',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR', 'EXPRESSIONS_OF_INTEREST_CREATOR']
            ],
            [
                'name' => 'EXPRESSIONSOFINTEREST_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model ExpressionsOfInterest',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR', 'EXPRESSIONS_OF_INTEREST_CREATOR', 'EXPRESSIONS_OF_INTEREST_READER']
            ],
            [
                'name' => 'EXPRESSIONSOFINTEREST_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model ExpressionsOfInterest',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR', UpdateOwnExprOfIntRule::className()]
            ],
            [
                'name' => 'EXPRESSIONSOFINTEREST_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission for model ExpressionsOfInterest',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR', DeleteOwnExprOfIntRule::className()]
            ]
        ];
    }

    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [

            // Widgets permissions for partnership profiles
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesAllAdmin::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconPartnershipProfilesAllAdmin',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR']
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconPartnershipProfilesDashboard',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_READER']
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesOwnInterest::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconPartnershipProfilesOwnInterest',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_READER']
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesAll::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconPartnershipProfilesAll',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_READER']
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesCreatedBy::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconPartnershipProfilesCreatedBy',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_CREATOR']
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesToValidate::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconPartnershipProfilesToValidate',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_VALIDATOR']
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesArchived::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconPartnershipProfilesArchived',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_READER']
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesClosed::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconPartnershipProfilesClosed',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_READER']
            ],

            // Widgets permissions for expressions of interest
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestAllAdmin::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconExpressionsOfInterestAllAdmin',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR']
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconExpressionsOfInterestDashboard',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR', 'EXPRESSIONS_OF_INTEREST_READER']
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestReceived::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconExpressionsOfInterestReceived',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR', 'EXPRESSIONS_OF_INTEREST_READER']
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestCreatedBy::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconExpressionsOfInterestCreatedBy',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR', 'EXPRESSIONS_OF_INTEREST_READER']
            ],

            // Widgets permissions for partnership profiles and expressions of interest
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconPartnerProfExprOfIntDashboard',
                'parent' => ['PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR']
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntPartProf::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconPartnerProfExprOfIntPartProf',
                'parent' => ['PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR']
            ],
            [
                'name' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntExprOfInt::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconPartnerProfExprOfIntExprOfInt',
                'parent' => ['PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR']
            ]
        ];
    }

    private function setWorkflowPermissions()
    {
        return [

            // Workflow permissions for partnership profiles
            [
                'name' => PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'PartnershipProfiles workflow status permission: Draft',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_CREATOR', 'PARTNERSHIP_PROFILES_VALIDATOR', 'PARTNERSHIP_PROFILES_FACILITATOR']
            ],
            [
                'name' => PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_TOVALIDATE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'PartnershipProfiles workflow status permission: To validate',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_CREATOR', 'PARTNERSHIP_PROFILES_VALIDATOR', 'PARTNERSHIP_PROFILES_FACILITATOR']
            ],
            [
                'name' => PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'PartnershipProfiles workflow status permission: Validated',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_VALIDATOR', 'PARTNERSHIP_PROFILES_FACILITATOR']
            ],
            [
                'name' => PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_FEEDBACKRECEIVED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'PartnershipProfiles workflow status permission: Feedback received',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR']
            ],
            [
                'name' => PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_ARCHIVED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'PartnershipProfiles workflow status permission: Archived',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR']
            ],
            [
                'name' => PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_CLOSED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'PartnershipProfiles workflow status permission: Closed',
                'parent' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_CREATOR']
            ],

            // Workflow permissions for expressions of interest
            [
                'name' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'ExpressionsOfInterest workflow status permission: Draft',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR', 'EXPRESSIONS_OF_INTEREST_CREATOR']
            ],
            [
                'name' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_ACTIVE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'ExpressionsOfInterest workflow status permission: Active',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR', 'EXPRESSIONS_OF_INTEREST_CREATOR']
            ],
            [
                'name' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_TOVALIDATE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'ExpressionsOfInterest workflow status permission: To validate',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_CREATOR']
            ],
            [
                'name' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_RELEVANT,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'ExpressionsOfInterest workflow status permission: Relevant',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_CREATOR']
            ],
            [
                'name' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_REJECTED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'ExpressionsOfInterest workflow status permission: Rejected',
                'parent' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR', 'PARTNERSHIP_PROFILES_CREATOR']
            ]
        ];
    }
}
