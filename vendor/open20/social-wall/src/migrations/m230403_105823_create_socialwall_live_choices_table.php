<?php

use yii\db\Migration;
use yii\db\sqlite\Schema;

class m230403_105823_create_socialwall_live_choices_table extends Migration{

    const TABLE_NAME = '{{%socialwall_live_choices}}';

    /**
     * @inheritdoc
     */
    public function safeUp() {

        if ($this->db->schema->getTableSchema(self::TABLE_NAME, true) === null) {
            $this->createTable(self::TABLE_NAME, [
                'id' => Schema::TYPE_PK,
                'socialwall_id' => $this->integer()->notNull()->comment('Related Social Wall'),
                'socialwall_tokens_id' => $this->integer()->notNull()->comment('Related Social Wall Tokens'),
                'keywords' => $this->string(500)->notNull()->comment('Keywords'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

            $this->addForeignKey('socialwall_live_choices_socialwall_id', self::TABLE_NAME, 'socialwall_id', 'socialwall', 'id');
            $this->addForeignKey('socialwall_live_choices_socialwall_tokens_id', self::TABLE_NAME, 'socialwall_tokens_id', 'socialwall_tokens', 'id');
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropForeignKey('socialwall_live_choices_socialwall_id', self::TABLE_NAME);
        $this->dropForeignKey('socialwall_live_choices_socialwall_tokens_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }

}
