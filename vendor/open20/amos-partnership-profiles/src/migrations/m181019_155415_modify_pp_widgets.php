<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m181019_155415_modify_pp_widgets extends Migration
{
    const TABLE = "amos_widgets";
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update(self::TABLE, ['child_of' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboardGeneral'], ['classname' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntDashboard', 'module' => 'partnershipprofiles']);
        $this->update(self::TABLE, ['child_of' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboardGeneral'], ['classname' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestAllAdmin', 'module' => 'partnershipprofiles']);
        $this->update(self::TABLE, ['child_of' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboardGeneral'], ['classname' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestDashboard', 'module' => 'partnershipprofiles']);
        $this->update(self::TABLE, ['child_of' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboardGeneral'], ['classname' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesAllAdmin', 'module' => 'partnershipprofiles']);
        $this->update(self::TABLE, ['child_of' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboardGeneral'], ['classname' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard', 'module' => 'partnershipprofiles']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update(self::TABLE, ['child_of' => null], ['classname' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntDashboard', 'module' => 'partnershipprofiles']);
        $this->update(self::TABLE, ['child_of' => null], ['classname' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestAllAdmin', 'module' => 'partnershipprofiles']);
        $this->update(self::TABLE, ['child_of' => null], ['classname' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestDashboard', 'module' => 'partnershipprofiles']);
        $this->update(self::TABLE, ['child_of' => null ], ['classname' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesAllAdmin', 'module' => 'partnershipprofiles']);
        $this->update(self::TABLE, ['child_of' => null], ['classname' => 'open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesDashboard', 'module' => 'partnershipprofiles']);


    }
}
