<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\db\Migration;
use yii\rbac\Permission;

/**
 * Class m200708_091800_update_tag_sending_frequency */
class m200708_091800_update_tag_sending_frequency extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('tag');
        if(!isset($table->columns['preference_sending_frequency_id'])) {
            $this->addColumn('tag', 'preference_sending_frequency_id', $this->integer(11)->null()->comment('Frequenza invio notifica')->after('icon_type'));
            $this->addForeignKey('fk_tag_preference_sending_frequency_1', 'tag', 'preference_sending_frequency_id', 'preference_sending_frequency', 'id');
            $this->update('tag',['preference_sending_frequency_id' => 1], ['like','codice', 'pctopic_%', false]);
        }
    }
    
    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('tag');
        if(isset($table->columns['preference_sending_frequency_id'])) {
            $this->dropForeignKey('fk_tag_preference_sending_frequency_1', 'tag');
            $this->dropColumn('tag', 'preference_sending_frequency_id');
        }
    }

}
