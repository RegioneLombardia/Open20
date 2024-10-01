<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m181008_160813_create_table_token_group extends Migration
{
    const TABLE_GROUP = "token_group";
    const TABLE_USER = "token_users";



    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_GROUP, true) === null) {
            $this->createTable(self::TABLE_GROUP, [
                'id' => Schema::TYPE_PK,
                'name' => $this->string()->comment('Name'),
                'Description' => $this->string()->comment('Description'),
                'url_redirect' => $this->string()->comment('Url redirect'),
                'target_class' => $this->string()->comment('Target class'),
                'target_id' => $this->integer()->defaultValue(null)->comment('Target id'),
                'consumable' => $this->integer()->defaultValue(0)->comment('Consumable'),
                'expire_date' => $this->dateTime()->comment('Expire date'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        }

        if ($this->db->schema->getTableSchema(self::TABLE_USER, true) === null) {
            $this->createTable(self::TABLE_USER, [
                'id' => Schema::TYPE_PK,
                'token_group_id' => $this->integer()->notNull()->comment('Token group'),
                'user_id' => $this->integer()->notNull()->comment('User'),
                'token' => $this->string()->comment('Token'),
                'used' => $this->integer()->defaultValue(0)->comment('Used'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_token_users_user_id1', self::TABLE_USER, 'user_id', 'user', 'id');
            $this->addForeignKey('fk_token_users_token_group_id1', self::TABLE_USER, 'token_group_id', self::TABLE_GROUP, 'id');

        }
    }



    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable(self::TABLE_USER);
        $this->dropTable(self::TABLE_GROUP);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
