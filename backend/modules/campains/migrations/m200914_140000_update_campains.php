<?php
use yii\db\Migration;

/**
 * Class m200914_140000_update_campains */
class m200914_140000_update_campains extends Migration
{
    public function safeUp()
    {
        $this->addColumn('preference_campain', 'pc_structure_id', $this->integer()->notNull()->comment('struttura')->after('status'));
        $this->update('preference_campain', ['pc_structure_id' => 1], ['>', 'id', '0']);
        $this->addForeignKey('fk_preference_campain_structures', 'preference_campain', 'pc_structure_id', 'preference_structure', 'id');
    }
    
    public function safeDown()
    {
        $this->dropForeignKey('fk_preference_campain_structures', 'preference_campain');
        $this->dropColumn('preference_campain', 'pc_structure_id');
    }

}
