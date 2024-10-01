<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m220516_150026_create_user_access_log extends Migration
{
    const TABLE  = "user_access_log";


    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE,
                [
                'id' => Schema::TYPE_PK,
                'user_id' => $this->integer()->comment('User'),
                'access_type' => $this->integer()->comment('Access type (1- login standard, 2- Idpc, 3- Impersonate'),
                'access_method' => $this->string()->comment('Access method'),
                'access_level' => $this->string()->comment('Access Level'),
                'impersonator_user_id' => $this->integer()->defaultValue(null)->comment('Impersonator'),
                'enabled' => $this->integer()->defaultValue(0)->comment('Enabled'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
                ],
                $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1'
                        : null);
        }


        $this->addForeignKey('fk_user_access_log_user_id', self::TABLE, 'user_id', 'user', 'id');
        $this->addForeignKey('fk_user_access_log_impersonator_user_id', self::TABLE, 'impersonator_user_id', 'user', 'id');

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