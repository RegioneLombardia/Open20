<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m210903_191126_create_table_profiles extends Migration
{
    const TABLE  = "user_profile_classes";
    const TABLE2 = "user_profile_classes_auth_mm";
    const TABLE3 = "user_profile_classes_user_mm";

    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE,
                [
                'id' => Schema::TYPE_PK,
                'name' => $this->string()->comment('Name'),
                'description' => $this->text()->comment('Description'),
                'code' => $this->string()->comment('Code'),
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
        if ($this->db->schema->getTableSchema(self::TABLE2, true) === null) {
            $this->createTable(self::TABLE2,
                [
                'id' => Schema::TYPE_PK,
                'user_profile_classes_id' => $this->integer()->comment('Profile'),
                'item_id' => $this->string()->notNull()->comment('Role/Permission'),
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
        if ($this->db->schema->getTableSchema(self::TABLE3, true) === null) {
            $this->createTable(self::TABLE3,
                [
                'id' => Schema::TYPE_PK,
                'user_id' => $this->integer()->comment('Profile'),
                'user_profile_classes_id' => $this->integer()->notNull()->comment('Role/Permission'),
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

        $this->addForeignKey('fk_user_profile_classes_auth_mm1', self::TABLE2, 'user_profile_classes_id', self::TABLE,
            'id');
        $this->addForeignKey('fk_user_profile_classes_user_mm1', self::TABLE3, 'user_id', 'user', 'id');
        $this->addForeignKey('fk_user_profile_classes_user_mm2', self::TABLE3, 'user_profile_classes_id', self::TABLE,
            'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable(self::TABLE3);
        $this->dropTable(self::TABLE2);
        $this->dropTable(self::TABLE);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}