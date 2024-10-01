<?php

use yii\db\Migration;
use yii\db\Schema;

class m220520_094026_create_table_ldap_user extends Migration
{
    const TABLE = "ldap_user";

    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(
                self::TABLE,
                [
                    'id' => Schema::TYPE_PK,
                    'username' => $this->string()->comment('Username'),
                    'email' => $this->string()->comment('Email'),
                    'first_name' => $this->string()->comment('First Name'),
                    'last_name' => $this->string()->comment('Last Name'),
                    'status' => $this->string()->comment('Status'),
                    'identity_id' => $this->string()->comment('Identity ID'),
                    'user_id' => $this->integer()->comment('User ID'),
                    'raw_data' => $this->string()->comment('RAW Data'),
                    'created_at' => $this->dateTime()->comment('Created at'),
                    'updated_at' => $this->dateTime()->comment('Updated at'),
                    'deleted_at' => $this->dateTime()->comment('Deleted at'),
                    'created_by' => $this->integer()->comment('Created by'),
                    'updated_by' => $this->integer()->comment('Updated at'),
                    'deleted_by' => $this->integer()->comment('Deleted at'),
                ],
                $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1'
                    : null
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable(self::TABLE);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}