<?php

use yii\db\Migration;
use yii\db\sqlite\Schema;

class m230411_163023_create_socialwall_posts_table extends Migration {

    const TABLE_NAME = '{{%socialwall_posts}}';

    /**
     * @inheritdoc
     */
    public function safeUp() {

        if ($this->db->schema->getTableSchema(self::TABLE_NAME, true) === null) {
            $this->createTable(self::TABLE_NAME, [
                'id' => Schema::TYPE_PK,
                'type' => $this->string()->notNull()->comment('Post type'),
//                'profile_name' => $this->string()->comment('Profile name'),
                'text' => $this->text()->comment('Post text'),
                'permalink' => $this->text(600)->comment('Permalink'),
                'publication_date' => $this->dateTime()->comment('Publication date on social'),
//                'profile_url' => $this->string(600)->comment('Profile url'),
                'media_type' => $this->string()->comment('Media type'),
                'search_tags' => $this->string(600)->comment('Search tags'),
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
