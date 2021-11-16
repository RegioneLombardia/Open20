<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m190725_162813_create_notification_query extends Migration
{

    const TABLE = 'notificationconf_network';



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
                'models_classname_id' => $this->integer()->notNull()->comment('Network'),
                'record_id' => $this->integer()->comment('Record id'),
                'email' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'sms' =>Schema::TYPE_INTEGER . " DEFAULT NULL",
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

            $this->addForeignKey('fk_notificationconf_netwotk_user_id1', self::TABLE, 'user_id', 'user', 'id');
            $this->addForeignKey('fk_notificconf_network_modelclss_id1', self::TABLE, 'models_classname_id', 'models_classname', 'id');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable('notificationconf_network');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
