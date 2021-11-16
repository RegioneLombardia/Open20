<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `event_group_referent`.
 */
class m200323_132022_create_table_dg extends Migration
{
    const TABLE    = '{{%event_group_referent}}';
    const TABLE_MM = '{{%event_group_referent_mm}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE,
                [
                'id' => Schema::TYPE_PK,
                'denominazione' => $this->string()->comment('Denominazione'),
                'descrizione' => $this->text()->comment('Descrizione'),
                'note' => $this->text()->comment('Note'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
                ],
                $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1'
                        : null);
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella ".self::TABLE." esiste gia'";
        }

        if ($this->db->schema->getTableSchema(self::TABLE_MM, true) === null) {
            $this->createTable(self::TABLE_MM,
                [
                'id' => Schema::TYPE_PK,
                'user_id' => $this->integer()->comment('Utente'),
                'event_group_referent_id' => $this->integer()->comment('Descrizione'),
                'exclude_from_query' => $this->integer()->defaultValue(0)->comment('Esclude l\'utente dalle query'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
                ],
                $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1'
                        : null);
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella ".self::TABLE_MM." esiste gia'";
        }

        $this->addForeignKey('fk_event_group_user_id1', self::TABLE_MM, 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk_event_group_referent_id1', self::TABLE_MM, 'event_group_referent_id', self::TABLE, 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_event_group_user_id1', self::TABLE_MM);
        $this->dropForeignKey('fk_event_group_referent_id1', self::TABLE_MM);
        $this->dropTable(self::TABLE_MM);
        $this->dropTable(self::TABLE);
    }
}