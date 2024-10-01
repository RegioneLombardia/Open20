<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m221012_135146_create_interoperability_tokens_table extends \yii\db\Migration
{
    const TABLE = '{{%utility_sso_tokens}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => $this->primaryKey(),
                'token' => $this->string(128)->null()->defaultValue(null)->comment('Token'),
                'user_id' => $this->integer(11)->notNull()->comment('User ID'),
                'impersonate' => $this->integer(11)->null()->comment('Impersonate'),
                'domain' => $this->string(128)->null()->defaultValue(null)->comment('Domain'),
                'created_by' => $this->integer()->null()->defaultValue(null),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_by' => $this->integer()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
                'deleted_by' => $this->integer()->null()->defaultValue(null),
                'deleted_at' => $this->dateTime()->null()->defaultValue(null),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }




        return true;
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable(self::TABLE);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');
        return true;
    }
}