<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200910_122013_create_table_landing_contents extends Migration
{
    const TABLE_LANDING_PROTAGONIST = "preference_landing_protagonist";
    const TABLE_LANDING_NEWS = "preference_landing_news";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {




        if ($this->db->schema->getTableSchema(self::TABLE_LANDING_PROTAGONIST, true) === null)
        {
            $this->createTable(self::TABLE_LANDING_PROTAGONIST, [
                'id' => Schema::TYPE_PK,
                'preference_landing_id' => $this->integer()->comment('Landing'),
                'name' => $this->string()->comment('Name'),
                'surname' => $this->string()->comment('Surname'),
                'company' => $this->string()->comment('Company'),
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
        $this->addForeignKey('fk_preference_landing_protagonis_pref_land_id1',self::TABLE_LANDING_PROTAGONIST, 'preference_landing_id', 'preference_landing', 'id');


        if ($this->db->schema->getTableSchema(self::TABLE_LANDING_NEWS, true) === null)
        {
            $this->createTable(self::TABLE_LANDING_NEWS, [
                'id' => Schema::TYPE_PK,
                'preference_landing_id' => $this->integer()->comment('Landing'),
                'news_id' => $this->integer()->comment('Name'),
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
        $this->addForeignKey('fk_preference_landing_news_pref_land_id1',self::TABLE_LANDING_NEWS, 'preference_landing_id', 'preference_landing', 'id');
        $this->addForeignKey('fk_preference_landing_news_news_id1',self::TABLE_LANDING_NEWS, 'news_id', 'news', 'id');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropTable(self::TABLE_LANDING_PROTAGONIST);
        $this->dropTable(self::TABLE_LANDING_NEWS);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
