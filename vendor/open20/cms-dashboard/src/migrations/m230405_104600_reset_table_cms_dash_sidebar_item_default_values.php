<?php
use yii\db\Migration;

/**
 * Class m230405_104600_reset_table_cms_dash_sidebar_item_default_values
 */
class m230405_104600_reset_table_cms_dash_sidebar_item_default_values extends Migration
{

    private $table = '{{%cms_dash_sidebar_item}}';

    public function safeUp()
    {

        $this->delete($this->table, [
            'label' => 'Report'
        ]);
        $this->delete($this->table, [
            'label' => 'Gestione portali'
        ]);
    }

    public function safeDown()
    {
        $this->insert($this->table, [
            'link' => '/metrics/operators',
            'link_shortcut' => null,
            'label' => 'Report',
            'description' => 'Report',
            'icon_name' => 'chart-pie',
            'isVisible' => 1,
            'isTargetBlank' => 1,
            'position' => 8
        ]);
        $this->insert($this->table, [
            'link' => 'javascript:void(0);',
            'link_shortcut' => null,
            'label' => 'Gestione portali',
            'description' => 'Gestione portali',
            'icon_name' => 'layers-triple',
            'isVisible' => 1,
            'isTargetBlank' => 0,
            'position' => 10
        ]);
    }
}