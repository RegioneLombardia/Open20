<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

/**
 * Class m170201_153634_remove_community_type_widget_and_permissions
 */
class m170201_153634_remove_community_type_widget_and_permissions extends \yii\db\Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $classNameWidget = 'open20\amos\community\widgets\icons\WidgetIconTipologiaCommunity';
        //remove from dashbords-widgets mm
        $this->delete('amos_user_dashboards_widget_mm', ['amos_widgets_classname' => $classNameWidget]);
        // remove from amos-widgets
        $this->delete('amos_widgets', ['classname' => $classNameWidget]);
        $this->delete('auth_item_child', ['child' => $classNameWidget]);
        $this->delete('auth_item', ['name' => $classNameWidget]);

        $this->delete('auth_item_child', "child LIKE 'TIPOLOGIACOMMUNITY_%'");
        $this->delete('auth_item', "name LIKE 'TIPOLOGIACOMMUNITY_%'");
        $this->delete('auth_assignment', "item_name LIKE 'TIPOLOGIACOMMUNITY_%'");

        $this->delete('auth_item_child', "child LIKE 'COMMUNITYTIPOLOGIACOMMUNITYMM_%'");
        $this->delete('auth_item', "name LIKE 'COMMUNITYTIPOLOGIACOMMUNITYMM_%'");
        $this->delete('auth_assignment', "item_name LIKE 'COMMUNITYTIPOLOGIACOMMUNITYMM_%'");

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "safe down not implemented for this migration";
        return true;
    }
}
