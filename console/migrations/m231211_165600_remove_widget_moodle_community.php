<?php

use yii\db\Migration;

/**
 * Class m230124_171900_serial_number_on_user_profile
 */
class m231211_165600_remove_widget_moodle_community extends Migration
{


    public function safeUp()
    {
        $this->update('amos_widgets', [
            'status' => 0
        ], [
            'classname' => 'open20\amos\moodle\widgets\icons\WidgetIconMoodle',
            'type' => 'ICON',
            'module' => 'community'
        ]);
    }

    public function safeDown()
    {
        $this->update('amos_widgets', [
            'status' => 1
        ], [
            'classname' => 'open20\amos\moodle\widgets\icons\WidgetIconMoodle',
            'type' => 'ICON',
            'module' => 'community'

        ]);
    }

}
