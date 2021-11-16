<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200327_145113_dashboard_visible_widgets3 extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['dashboard_visible' => false], ['classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral']);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->update('amos_widgets', ['dashboard_visible' => false], ['classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral']);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
