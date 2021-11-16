<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200330_163013_dashboard_visible_widgets_graphics extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['dashboard_visible' => false], ['module' =>'sondaggi', 'classname' => 'open20\amos\sondaggi\widgets\graphics\WidgetGraphicsUltimiSondaggi']);
        $this->update('amos_widgets', ['dashboard_visible' => false], ['module' =>'news', 'classname' => 'open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews']);
        $this->update('amos_widgets', ['dashboard_visible' => false], ['module' =>'documenti', 'classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti']);
        $this->update('amos_widgets', ['dashboard_visible' => false], ['module' =>'discussioni', 'classname' => 'open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni']);
        $this->update('amos_widgets', ['dashboard_visible' => false], ['module' =>'community', 'classname' => 'open20\amos\community\widgets\graphics\WidgetGraphicsMyCommunities']);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->update('amos_widgets', ['dashboard_visible' => true], ['module' =>'sondaggi', 'classname' => 'open20\amos\sondaggi\widgets\graphics\WidgetGraphicsUltimiSondaggi']);
        $this->update('amos_widgets', ['dashboard_visible' => true], ['module' =>'news', 'classname' => 'open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews']);
        $this->update('amos_widgets', ['dashboard_visible' => true], ['module' =>'documenti', 'classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti']);
        $this->update('amos_widgets', ['dashboard_visible' => true], ['module' =>'discussioni', 'classname' => 'open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni']);
        $this->update('amos_widgets', ['dashboard_visible' => true], ['module' =>'community', 'classname' => 'open20\amos\community\widgets\graphics\WidgetGraphicsMyCommunities']);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
