<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m191126_162113_create_table_user_profile_external_facilitator extends Migration
{
    const TABLE = "user_profile_external_facilitator";

    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'user_profile_id' => $this->integer()->comment('User profile'),
                'external_facilitator_id' => $this->integer()->comment('Facilitator'),
                'status' => $this->integer()->comment('Status'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        }
        $this->addForeignKey('fk_user_profile_external_facilitator_ext_facil_id',self::TABLE, 'external_facilitator_id', 'user_profile', 'id');
        $this->addForeignKey('fk_user_profile_external_facilitator_us_prof_id',self::TABLE, 'user_profile_id', 'user_profile', 'id');

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
