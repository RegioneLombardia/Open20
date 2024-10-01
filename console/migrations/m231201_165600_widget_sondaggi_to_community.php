<?php
use yii\db\Migration;

/**
 * Class m230124_171900_serial_number_on_user_profile
 */
class m231201_165600_widget_sondaggi_to_community extends Migration
{


    public function safeUp()
    {
        $this->insert('amos_widgets',[
            'classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral',
            'dashboard_visible' => 1,
            'type' => 'ICON',
            'module' => 'community',
            'status' => 1,
            'sub_dashboard' => 1,
            'default_order' => 7
        ]);
    }

    public function safeDown()
    {
        $this->delete('amos_widgets',[
            'classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral',
            'dashboard_visible' => 1,
            'type' => 'ICON',
            'module' => 'community',
            'status' => 1,
            'sub_dashboard' => 1,
            'default_order' => 7
        ]);
    }

}
