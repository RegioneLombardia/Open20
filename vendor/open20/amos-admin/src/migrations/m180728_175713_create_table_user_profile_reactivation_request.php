<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m180728_175713_create_table_user_profile_reactivation_request extends Migration
{
    const TABLE = "user_profile_reactivation_request";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'user_profile_id' => $this->integer()->notNull(),
                'message' => $this->text()->defaultValue(null),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_user_profile_reactivation_user_profile_id1', self::TABLE, 'user_profile_id', 'user_profile', 'id');

        }
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
