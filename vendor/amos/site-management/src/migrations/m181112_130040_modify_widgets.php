<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m180308_094940_add_foreign_keys_site_management_metadata
 */
class m181112_130040_modify_widgets extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['dashboard_visible' => 0, 'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementAdministration'],
            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementSection' ]);
        $this->update('amos_widgets', ['dashboard_visible' => 0,'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementAdministration'],
            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementTemplate']);
        $this->update('amos_widgets', ['dashboard_visible' => 0,'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementAdministration'],
            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementFields']);

        $this->update('amos_widgets', ['dashboard_visible' => 0, 'default_order' => 60, 'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementAdministration'],
            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLanding']);
//        $this->update('amos_widgets', ['dashboard_visible' => 0,'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLandingFather'],
//            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLandingPubblication']);

        $this->update('amos_widgets', ['dashboard_visible' => 0,'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementContainerFather'],
            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementElement']);
        $this->update('amos_widgets', ['dashboard_visible' => 0,'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementContainerFather'],
            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementContainer']);




        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update('amos_widgets', ['dashboard_visible' => 1,'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard'],
            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementSection' ]);
        $this->update('amos_widgets', ['dashboard_visible' => 1,'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard'],
            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementTemplate']);
        $this->update('amos_widgets', ['dashboard_visible' => 1,'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard'],
            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementFields']);

        $this->update('amos_widgets', ['dashboard_visible' => 1,'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard'],
            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLanding']);
//        $this->update('amos_widgets', ['dashboard_visible' => 1,'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard'],
//            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLandingPubblication']);

        $this->update('amos_widgets', ['dashboard_visible' => 1,'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard'],
            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementElement']);
        $this->update('amos_widgets', ['dashboard_visible' => 1,'child_of' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard'],
            ['classname' => 'amos\sitemanagement\widgets\icons\WidgetIconSiteManagementContainer']);


        return true;
    }
}
