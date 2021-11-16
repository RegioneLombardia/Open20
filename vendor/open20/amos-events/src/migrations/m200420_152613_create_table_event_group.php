<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200420_152613_create_table_event_group extends Migration
{
    const TABLE = "event_container";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'title' => $this->string()->comment('Title'),
                'description' => $this->text()->comment('Description'),
                'luya_template_id' => $this->integer()->comment('template'),
                'luya_page_id' => $this->integer()->comment('Page'),
                'url' => $this->string()->comment('Url'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

            $this->addColumn('event', 'event_container_order', $this->integer()->defaultValue(null)->after('community_id'));
            $this->addColumn('event', 'event_container_id', $this->integer()->defaultValue(null)->after('community_id'));
            $this->addForeignKey('fk_event_event_container_id_id1','event', 'event_container_id', 'event_container', 'id');
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }



    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropForeignKey('fk_event_event_container_id_id1','event');
        $this->dropColumn('event', 'event_container_order');
        $this->dropColumn('event', 'event_container_id');

        $this->dropTable(self::TABLE);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
