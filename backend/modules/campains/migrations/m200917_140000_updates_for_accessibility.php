<?php
use yii\db\Migration;

/**
 * Class m200917_140000_updates_for_accessibility */
class m200917_140000_updates_for_accessibility extends Migration
{
    public function safeUp()
    {
        $this->addColumn('preference_mailup_template', 'default_link_text', $this->string(255)->comment('default link text')->after('separator'));
        $this->addColumn('preference_mailup_template', 'colors_choice', $this->text()->comment('color list to choose')->after('default_link_text')); 
        $this->addColumn('preference_campain_container', 'content_image_alt', $this->string(255)->comment('attribute ALT on image')->after('separator_title')); 
        $this->addColumn('preference_campain_container', 'content_news_link_title', $this->string(255)->comment('attribute title on link')->after('content_news_link')); 
        $this->addColumn('preference_campain_container', 'content_news_link_scope', $this->string(255)->comment('scope on link')->after('content_news_link_title')); 
    }
    
    public function safeDown()
    {
        $this->dropColumn('preference_mailup_template', 'default_link_text');
        $this->dropColumn('preference_mailup_template', 'colors_choice');
        $this->dropColumn('preference_campain_container', 'content_image_alt');
        $this->dropColumn('preference_campain_container', 'content_news_link_title');
        $this->dropColumn('preference_campain_container', 'content_news_link_scope');
    }

}
