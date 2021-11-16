<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m210112_104513_create_table_user_profile_otp_code extends Migration
{
    const TABLE = "user_otp_code";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'user_id' => $this->integer()->defaultValue(null)->comment('User'),
                'session_id' => $this->string()->comment('Session'),
                'otp_code' => $this->string()->comment('OTP'),
                'type' => $this->string()->comment('Type'),
                'expire' => $this->dateTime()->comment('Expire at'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        }
        $this->addForeignKey('fk_user_otp_code_user_id1',self::TABLE, 'user_id', 'user', 'id');
    }



    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable(self::TABLE);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
