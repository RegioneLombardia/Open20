<?php

use yii\db\Migration;
use \open20\amos\favorites\widgets\ListFavoriteUrlsWidget;
/**
 * Class m230327_182700_update_table_cms_dash_sidebar_item_target_blank_value
 */
class m230327_182700_update_table_cms_dash_sidebar_item_target_blank_value extends Migration
{
    private $table = '{{%cms_dash_sidebar_item}}';

    public function up()
    {
        $this->update($this->table, ['isTargetBlank' => 0]);
    }

    public function down()
    {
        $this->update($this->table, ['isTargetBlank' => 1]);
    }
}