<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m190731_173113_create_personalized_fields_default_val extends Migration
{

    const TABLE_FIELDS_DEFAULT = 'community_user_field_default_val';

    /**
     * @inheritdoc
     */
    public function up()
    {


        if ($this->db->schema->getTableSchema(self::TABLE_FIELDS_DEFAULT, true) === null)
        {
            $this->createTable(self::TABLE_FIELDS_DEFAULT, [
                'id' => Schema::TYPE_PK,
                'community_user_field_id' => $this->string()->notNull()->comment('Field'),
                'value' => $this->string()->comment('Value'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->insert('community_user_field_type', ['id' => 4, 'description' => 'Tendina singola scelta', 'type' => 'select_single']);

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }



    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable('community_user_field_default_val');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
