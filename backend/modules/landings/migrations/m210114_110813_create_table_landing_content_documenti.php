<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `m210114_110813_create_table_landing_content_documenti`.
 */
class m210114_110813_create_table_landing_content_documenti extends Migration
{
  
    const TABLE_LANDING_DOCUMENTI = "preference_landing_documenti";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        if ($this->db->schema->getTableSchema(self::TABLE_LANDING_DOCUMENTI, true) === null)
        {
            $this->createTable(self::TABLE_LANDING_DOCUMENTI, [
                'id' => Schema::TYPE_PK,
                'preference_landing_id' => $this->integer()->comment('Landing'),
                'documenti_id' => $this->integer()->comment('Name'),
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
        $this->addForeignKey('fk_preference_landing_documenti_pref_land_id1',self::TABLE_LANDING_DOCUMENTI, 'preference_landing_id', 'preference_landing', 'id');
        $this->addForeignKey('fk_preference_landing_documenti_documenti_id1',self::TABLE_LANDING_DOCUMENTI, 'documenti_id', 'documenti', 'id');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropTable(self::TABLE_LANDING_DOCUMENTI);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
