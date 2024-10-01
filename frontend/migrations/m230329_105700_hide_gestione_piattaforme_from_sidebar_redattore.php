<?php

use yii\db\Migration;
/**
 * Class m230329_105700_hide_gestione_piattaforme_from_sidebar_redattore
 */
class m230329_105700_hide_gestione_piattaforme_from_sidebar_redattore extends Migration
{
    private $table = '{{%cms_dash_sidebar_item}}';

    public function up()
    {
        $this->update($this->table, ['isVisible' => 0], ['id' => 10]);
    }

    public function down()
    {
        $this->update($this->table, ['isVisible' => 1], ['id' => 10]);
    }
}