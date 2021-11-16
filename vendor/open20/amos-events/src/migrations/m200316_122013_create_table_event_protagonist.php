<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200316_122013_create_table_event_protagonist extends Migration
{
    const TABLE_EVENT_LANDING = "event_landing";
    const TABLE_EVENT_LANDING_PROTAGONIST = "event_landing_protagonist";
    const TABLE_EVENT_LANDING_NEWS = "event_landing_news";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        if ($this->db->schema->getTableSchema(self::TABLE_EVENT_LANDING, true) === null)
        {
            $this->createTable(self::TABLE_EVENT_LANDING, [
                'id' => Schema::TYPE_PK,
                'event_id' => $this->integer()->comment('Landing'),
                'description' => $this->text()->comment('Description'),
                'schedule' => $this->text()->comment('Schedule'),
                'image_slider_id' => $this->integer()->comment('Slider images'),
                'video_slider_id' => $this->integer()->comment('Slider videos'),
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

        $this->addForeignKey('fk_event_landing_event_id1','event_landing', 'event_id', 'event', 'id');

        if ($this->db->schema->getTableSchema(self::TABLE_EVENT_LANDING_PROTAGONIST, true) === null)
        {
            $this->createTable(self::TABLE_EVENT_LANDING_PROTAGONIST, [
                'id' => Schema::TYPE_PK,
                'event_landing_id' => $this->integer()->comment('Landing'),
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
        $this->addForeignKey('fk_event_landing_protagonis_event_land_id1',self::TABLE_EVENT_LANDING_PROTAGONIST, 'event_landing_id', 'event_landing', 'id');


        if ($this->db->schema->getTableSchema(self::TABLE_EVENT_LANDING_NEWS, true) === null)
        {
            $this->createTable(self::TABLE_EVENT_LANDING_NEWS, [
                'id' => Schema::TYPE_PK,
                'event_landing_id' => $this->integer()->comment('Landing'),
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
        $this->addForeignKey('fk_event_landing_news_event_land_id1',self::TABLE_EVENT_LANDING_NEWS, 'event_landing_id', 'event_landing', 'id');
        $this->addForeignKey('fk_event_landing_news_news_id1',self::TABLE_EVENT_LANDING_NEWS, 'news_id', 'news', 'id');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropTable(self::TABLE_EVENT_LANDING);
        $this->dropTable(self::TABLE_EVENT_LANDING_PROTAGONIST);
        $this->dropTable(self::TABLE_EVENT_LANDING_NEWS);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
