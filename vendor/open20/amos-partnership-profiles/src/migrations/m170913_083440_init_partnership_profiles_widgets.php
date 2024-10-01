<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;
use yii\helpers\ArrayHelper;

/**
 * Class m170913_083440_init_partnership_profiles_widgets
 */
class m170913_083440_init_partnership_profiles_widgets extends AmosMigrationWidgets
{
    const PARTNERSHIPPROFILESADMIN = 'partnershipprofilesadmin';
    const PARTNERSHIPPROFILES = 'partnershipprofiles';
    const EXPRESSIONSOFINTEREST = 'expressionsofinterest';
    const PARTNERPROFEXPROFINT = 'partnerprofexprofint';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = ArrayHelper::merge(
            $this->partnershipProfilesWidgets(),
            $this->expressionsOfInterestWidgets(),
            $this->partnerProfExprOfIntWidgets()
        );
    }

    /**
     * @return array
     */
    private function partnershipProfilesWidgets()
    {
        return [
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesAllAdmin::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::PARTNERSHIPPROFILESADMIN,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'dashboard_visible' => 1,
                'default_order' => 1
            ],
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::PARTNERSHIPPROFILES,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'dashboard_visible' => 1,
                'default_order' => 1
            ],
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesOwnInterest::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::PARTNERSHIPPROFILES,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 10
            ],
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesAll::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::PARTNERSHIPPROFILES,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 20
            ],
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesCreatedBy::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::PARTNERSHIPPROFILES,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 30
            ],
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesToValidate::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::PARTNERSHIPPROFILES,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 40
            ],
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesArchived::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::PARTNERSHIPPROFILES,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 50
            ],
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesClosed::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::PARTNERSHIPPROFILES,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 60
            ]
        ];
    }

    /**
     * @return array
     */
    private function expressionsOfInterestWidgets()
    {
        return [
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestAllAdmin::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::EXPRESSIONSOFINTEREST,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'dashboard_visible' => 1,
                'default_order' => 1
            ],
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::EXPRESSIONSOFINTEREST,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'dashboard_visible' => 1,
                'default_order' => 1
            ],
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestReceived::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::EXPRESSIONSOFINTEREST,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 10
            ],
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestCreatedBy::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::EXPRESSIONSOFINTEREST,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 20
            ]
        ];
    }

    /**
     * @return array
     */
    private function partnerProfExprOfIntWidgets()
    {
        return [
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::PARTNERPROFEXPROFINT,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'dashboard_visible' => 1,
                'default_order' => 1
            ],
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntPartProf::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::PARTNERPROFEXPROFINT,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 10
            ],
            [
                'classname' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntExprOfInt::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::PARTNERPROFEXPROFINT,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 20
            ]
        ];
    }
}
