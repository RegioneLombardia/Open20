<?php

use yii\db\Migration;
use \open20\amos\favorites\widgets\ListFavoriteUrlsWidget;
/**
 * Class m230906_110800_update_default_link_news_value_table_cms_dash_sidebar_item
 */
class m230906_110800_update_default_link_news_value_table_cms_dash_sidebar_item extends Migration
{
    private $table = '{{%cms_dash_sidebar_item}}';

    public function up()
    {
        $this->update($this->table, ['link' => '/news/news/admin-all-news'], ['link' => '/news/news/redaction-all-news']);
    }

    public function down()
    {
        $this->update($this->table, ['link' => '/news/news/redaction-all-news'], ['link' => '/news/news/admin-all-news']);
    }
}