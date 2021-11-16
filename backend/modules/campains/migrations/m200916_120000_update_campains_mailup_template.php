<?php
use yii\db\Migration;

/**
 * Class m200916_120000_update_campains_mailup_template */
class m200916_120000_update_campains_mailup_template extends Migration
{
    public function safeUp()
    {
        $this->addColumn('preference_mailup_template', 'mailup_template_preview_id', $this->integer()->comment('preview')->after('mailup_template_id'));
        
    }
    
    public function safeDown()
    {
        $this->dropColumn('preference_mailup_template', 'mailup_template_preview_id');
    }

}
