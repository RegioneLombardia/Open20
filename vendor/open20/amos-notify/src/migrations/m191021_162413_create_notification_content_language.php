<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m191021_162413_create_notification_content_language extends Migration
{

    const TABLE = 'notification_content_language';



    /**
     * @inheritdoc
     */
    public function up()
    {


        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'language' => $this->string()->notNull()->comment('Lingua'),
                'models_classname_id' => $this->integer()->notNull()->comment('Network'),
                'record_id' => $this->integer()->comment('Record id'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

            $this->addForeignKey('fk_notification_content_language_modelclss_id1', self::TABLE, 'models_classname_id', 'models_classname', 'id');
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
        $this->dropTable('notification_content_language');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
