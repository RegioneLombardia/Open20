<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m181108_161715_regroup_widgets_translation
 */
class m181108_161715_regroup_widgets_translation extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['dashboard_visible' => 0, 'child_of' => 'open20\amos\dashboard\widgets\icons\WidgetIconManagement'], ['classname' => 'open20\amos\translation\widgets\icons\WidgetIconTranslation']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update('amos_widgets', ['dashboard_visible' => 1, 'child_of' => null], ['classname' => 'open20\amos\translation\widgets\icons\WidgetIconTranslation']);
    }
}
