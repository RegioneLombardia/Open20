<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m190606_173613_create_personalized_fields_tables extends Migration
{

    const TABLE_FIELDS_TYPE = 'community_user_field_type';
    const TABLE_FIELDS_VALUES = 'community_user_field_val';
    const TABLE_EXTRA_FIELDS = 'community_user_field';


    /**
     * @inheritdoc
     */
    public function up()
    {


        if ($this->db->schema->getTableSchema(self::TABLE_FIELDS_TYPE, true) === null)
        {
            $this->createTable(self::TABLE_FIELDS_TYPE, [
                'id' => Schema::TYPE_PK,
                'type' => $this->string()->notNull()->comment('Type'),
                'description' => $this->string()->comment('Description'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        if ($this->db->schema->getTableSchema(self::TABLE_EXTRA_FIELDS, true) === null)
        {
            $this->createTable(self::TABLE_EXTRA_FIELDS, [
                'id' => Schema::TYPE_PK,
                'community_id' => $this->integer()->notNull()->comment('Member'),
                'user_field_type_id' => $this->integer()->notNull()->comment('Type of field'),
                'name' => $this->string()->comment('Name'),
                'description' => $this->text()->comment('Description'),
                'tooltip' => $this->text()->comment('Description'),
                'required' => $this->integer(1)->defaultValue(0)->comment('Required'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_community_field_type_field_type_id1', self::TABLE_EXTRA_FIELDS, 'user_field_type_id', self::TABLE_FIELDS_TYPE, 'id');
            $this->addForeignKey('fk_community_field_community_id1', self::TABLE_EXTRA_FIELDS, 'community_id', 'community', 'id');


        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }


        if ($this->db->schema->getTableSchema(self::TABLE_FIELDS_VALUES, true) === null)
        {
            $this->createTable(self::TABLE_FIELDS_VALUES, [
                'id' => Schema::TYPE_PK,
                'user_id' => $this->integer()->notNull()->comment('User'),
                'user_field_id' => $this->integer()->notNull()->comment('Field'),
                'value' => $this->text()->notNull()->comment('Value'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_community_user_field_val_user_id1', self::TABLE_FIELDS_VALUES, 'user_id', 'user', 'id');
            $this->addForeignKey('fk_community_user_field_val_field_id1', self::TABLE_FIELDS_VALUES, 'user_field_id', self::TABLE_EXTRA_FIELDS, 'id');

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        $this->insert(self::TABLE_FIELDS_TYPE , ['id' => 1, 'description' => 'String', 'type' => 'string']);
        $this->insert(self::TABLE_FIELDS_TYPE , ['id' => 2, 'description' => 'Text', 'type' => 'text']);
        $this->insert(self::TABLE_FIELDS_TYPE , ['id' => 3, 'description' => 'Date', 'type' => 'date']);


    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');


        $this->dropTable(self::TABLE_FIELDS_VALUES);
        $this->dropTable(self::TABLE_FIELDS_TYPE);
        $this->dropTable(self::TABLE_EXTRA_FIELDS);





        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
