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
class m210422_161715_widget_translations extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['child_of' => null ], ['classname' => 'open20\amos\translation\widgets\icons\WidgetIconTranslation']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update('amos_widgets', ['child_of' => 'open20\amos\dashboard\widgets\icons\WidgetIconManagement' ], ['classname' => 'open20\amos\translation\widgets\icons\WidgetIconTranslation']);
    }
}
