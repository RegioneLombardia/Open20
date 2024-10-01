<?php

use yii\db\Migration;
use yii\db\sqlite\Schema;

class m230403_105523_create_socialwall_table extends Migration{

    const TABLE_NAME = '{{%socialwall}}';

    /**
     * @inheritdoc
     */
    public function safeUp() {

        if ($this->db->schema->getTableSchema(self::TABLE_NAME, true) === null) {
            $this->createTable(self::TABLE_NAME, [
                'id' => Schema::TYPE_PK,
                'module_id' => $this->string()->notNull()->comment('Related Module'),
                'module_record_id' => $this->integer()->notNull()->comment('Related Module Id'),
                'type' => $this->string(30)->notNull()->comment('Type'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropTable(self::TABLE_NAME);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }

}
