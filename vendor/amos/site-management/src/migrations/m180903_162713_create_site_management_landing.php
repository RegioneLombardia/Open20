<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m180903_162713_create_site_management_landing extends Migration
{
    const TABLE_LANDING = "site_management_landing";
    const TABLE_PUBBLICATION_LANDING = "site_management_landing_pubblication";




    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_LANDING, true) === null)
        {
            $this->createTable(self::TABLE_LANDING, [
                'id' => Schema::TYPE_PK,
                'name' => $this->string()->comment('Name'),
                'description' => $this->string()->comment('Description'),
                'view_path' => $this->string()->comment('View Path / Url'),
                'layout_path' => $this->string()->comment('Layout path'),
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
        if ($this->db->schema->getTableSchema(self::TABLE_PUBBLICATION_LANDING, true) === null)
        {
            $this->createTable(self::TABLE_PUBBLICATION_LANDING, [
                'id' => Schema::TYPE_PK,
                'url' => $this->string()->notNull()->comment('Url target'),
                'landing_id' => $this->integer()->notNull()->comment('Landing'),
                'start_date' => $this->dateTime()->comment('Start date'),
                'end_date' => $this->dateTime()->comment('End date'),
                'start_time' => $this->time()->comment('Start time'),
                'end_time' => $this->dateTime()->comment('End time'),
                'expire_days_cookie' => $this->integer()->comment('Expire days cookie'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_sm_landing_id1', self::TABLE_PUBBLICATION_LANDING, 'landing_id', self::TABLE_LANDING, 'id');

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
        $this->dropTable(self::TABLE_LANDING);
        $this->dropTable(self::TABLE_PUBBLICATION_LANDING);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
