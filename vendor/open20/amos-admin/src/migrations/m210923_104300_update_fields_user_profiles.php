<?php
use yii\db\Migration;
/**
 * Class m210923_104300_update_fields_user_profiles
 */
class m210923_104300_update_fields_user_profiles extends Migration
{

    private $table =  '{{%user_profile}}';
    
    public function safeUp()
    {
        $table_schema = Yii::$app->db->schema->getTableSchema($this->table);
        
        if (!isset($table_schema->columns['codice_ipa'])) {
            $this->addColumn($this->table, 'codice_ipa', $this->string(255)->null()->comment('Codice IPA')->after('sesso'));
        }
        if (!isset($table_schema->columns['email_istituzionale'])) {
            $this->addColumn($this->table, 'email_istituzionale', $this->string(255)->null()->comment('verificato la correttezza delle informazioni')->after('sesso'));
        }
        if (!isset($table_schema->columns['correttezza_info'])) {
            $this->addColumn($this->table, 'correttezza_info', $this->boolean()->defaultValue('0')->null()->comment('Codice IPA')->after('sesso'));
        }
        
        
        
        return true;

    }

    public function safeDown()
    {
        $this->dropColumn($this->table, 'codice_ipa');
        $this->dropColumn($this->table, 'email_istituzionale');
        $this->dropColumn($this->table, 'correttezza_info');
    }
}
