<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

use yii\db\Migration;
use yii\db\Schema;

class m201111_145608_create_table_notification_conf_content extends Migration
{

    const TABLE = 'notification_conf_content';


    /**
     * @inheritdoc
     */
    public function up()
    {


        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => $this->primaryKey(),
                'notification_conf_id' => $this->integer()->defaultValue(null)->comment('Notification conf'),
                'models_classname_id' => $this->integer()->defaultValue(null)->comment('Content classname'),
                'content_id' => $this->integer()->defaultValue(null)->comment('Content id'),
                'email' => $this->integer(1)->defaultValue(1)->comment('Enable email'),
                'push_notification' => $this->integer(1)->defaultValue(1)->comment('Enable email'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

            $this->addForeignKey('fk_notification_conf_content_notification_content_id', self::TABLE, 'notification_conf_id', 'notificationconf', 'id');
            $this->addForeignKey('fk_notification_conf_content_models_class_id', self::TABLE, 'models_classname_id', 'models_classname', 'id');

        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }


    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable('notification_conf_content');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
