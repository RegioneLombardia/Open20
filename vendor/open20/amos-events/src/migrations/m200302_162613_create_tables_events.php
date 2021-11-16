<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200302_162613_create_tables_events extends Migration
{
    const TABLE_EVENT_CATEGORY = "event_category";
    const TABLE_EVENT_LOCATION = "event_location";
    const TABLE_EVENT_LOCATION_ENTRANCE = "event_location_entrances";
    const TABLE_EVENT_PLACES = "event_location_places";






    /**
     * @inheritdoc
     */
    public function up()
    {


        if ($this->db->schema->getTableSchema(self::TABLE_EVENT_CATEGORY, true) === null)
        {
            $this->createTable(self::TABLE_EVENT_CATEGORY, [
                'id' => Schema::TYPE_PK,
                'name' => $this->string()->comment('Name'),
                'description' => $this->text()->comment('Description'),
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


        if ($this->db->schema->getTableSchema(self::TABLE_EVENT_LOCATION, true) === null)
        {
            $this->createTable(self::TABLE_EVENT_LOCATION, [
                'id' => Schema::TYPE_PK,
                'name' => $this->string()->comment('Name'),
                'description' => $this->text()->comment('Description'),
                'event_place_id' => $this->string()->comment('Place'),
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



        if ($this->db->schema->getTableSchema(self::TABLE_EVENT_LOCATION_ENTRANCE, true) === null)
        {
            $this->createTable(self::TABLE_EVENT_LOCATION_ENTRANCE, [
                'id' => Schema::TYPE_PK,
                'event_location_id' => $this->integer()->comment('Location'),
                'name' => $this->string()->comment('Name'),
                'description' => $this->text()->comment('Description'),
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

        $this->addForeignKey('fk_event_entrances_event_location_id1',self::TABLE_EVENT_LOCATION_ENTRANCE, 'event_location_id', 'event_location','id');
        $this->addColumn('event', 'event_category_id', $this->integer()->after('description'));
        $this->addColumn('event', 'event_location_id', $this->integer()->after('description'));
        $this->addForeignKey('fk_event_event_category_id1','event', 'event_category_id', 'event_category','id');
        $this->addForeignKey('fk_event_event_location_id1','event', 'event_location_id', 'event_location','id');


        $charset = ($this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);

        if ($this->db->schema->getTableSchema(self::TABLE_EVENT_PLACES, true) === null) {
            $this->createTable(self::TABLE_EVENT_PLACES, [
                'place_id' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Codice recupero place'",
                'place_response' => Schema::TYPE_TEXT . " NULL COMMENT 'Risposta'",
                'place_type' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Tipologia di recupero dati'",
                'country' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Paese'",
                'region' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Regione'",
                'province' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Provincia'",
                'postal_code' => Schema::TYPE_STRING . "(255) NULL COMMENT 'CAP'",
                'city' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Città'",
                'address' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Via/Piazza'",
                'street_number' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Numero civico'",
                'latitude' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Latitudine'",
                'longitude' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Longitudine'",
            ], $charset);

            $this->addPrimaryKey("pk_".self::TABLE_EVENT_PLACES."_place_id", self::TABLE_EVENT_PLACES, "place_id");
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropForeignKey('fk_event_event_category_id1','event');
        $this->dropForeignKey('fk_event_event_location_id1','event');
        $this->dropTable(self::TABLE_EVENT_CATEGORY);
        $this->dropTable(self::TABLE_EVENT_LOCATION);
        $this->dropTable(self::TABLE_EVENT_LOCATION_ENTRANCE);

        $this->dropColumn('event', 'event_category_id');
        $this->dropColumn('event', 'event_location_id');


        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
