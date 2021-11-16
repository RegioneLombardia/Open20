<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200326_180613_dashboard_visible_widgets2 extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('amos_widgets', [
            'classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral',
            'type' => 'ICON',
            'module' => 'community',
            'dashboard_visible' => false,
            'sub_dashboard' => true,
            'status' => true,
            'default_order' => 200
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->insert('amos_widgets', [
            'classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral',
            'type' => 'ICON',
            'module' => 'community',
            'dashboard_visible' => false,
            'sub_dashboard' => true,
            'status' => true,
            'default_order' => 200
        ]);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
