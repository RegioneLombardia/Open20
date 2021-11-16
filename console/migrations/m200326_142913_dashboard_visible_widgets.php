<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200326_142913_dashboard_visible_widgets extends Migration
{
    const TABLE_EVENT_CATEGORY = "event_category";



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['dashboard_visible' => false], ['classname' => 'open20\amos\community\widgets\icons\WidgetIconCommunityDashboard']);
        $this->update('amos_widgets', ['dashboard_visible' => false], ['classname' => 'open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard']);
        $this->update('amos_widgets', ['dashboard_visible' => false], ['classname' => 'open20\amos\news\widgets\icons\WidgetIconNewsDashboard']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->update('amos_widgets', ['dashboard_visible' => true], ['classname' => 'open20\amos\community\widgets\icons\WidgetIconCommunityDashboard']);
        $this->update('amos_widgets', ['dashboard_visible' => true], ['classname' => 'open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard']);
        $this->update('amos_widgets', ['dashboard_visible' => true], ['classname' => 'open20\amos\news\widgets\icons\WidgetIconNewsDashboard']);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
