<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m171221_081234_set_dashboard_visible_widget
 */
class m171221_081234_set_dashboard_visible_widget extends Migration
{
    const TABLE = '{{%amos_widgets}}';

    public function safeUp()
    {
        $this->update(self::TABLE, ['dashboard_visible' => 1],
            ['classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className()]);
        return true;
    }

    public function safeDown()
    {
        $this->update(self::TABLE, ['dashboard_visible' => 0],
            ['classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className()]);
        return true;
    }
}
