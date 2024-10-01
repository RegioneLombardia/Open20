<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m191022_155813_create_notification_language_preferences extends Migration
{

    const TABLE = 'notification_language_preferences';



    /**
     * @inheritdoc
     */
    public function up()
    {


        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'user_id' => $this->integer()->notNull()->comment('User'),
                'language' => $this->string()->notNull()->comment('Language'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

            $this->addForeignKey('fk_notification_languagepref_user_id_id1', self::TABLE, 'user_id', 'user', 'id');
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
        $this->dropTable('notification_language_preferences');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
