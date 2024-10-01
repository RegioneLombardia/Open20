<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m230123_174313_create_favorites extends Migration
{
    const TABLE = "favorite";




    /**
     * @inheritdoc
     */
    public function up()
    {

        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'user_id' => $this->integer()->comment('User'),
                'title' => $this->string()->comment('Title'),
                'url' => $this->string()->comment('Url'),
                'module' => $this->string()->comment('Module'),
                'controller' => $this->string()->comment('Controller'),
                'content_classname' => $this->string()->comment('Classname'),
                'content_id' => $this->integer()->comment('Content id'),
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

        $this->addForeignKey('fk_favorite_user_id1',self::TABLE,'user_id', 'user', 'id');


    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable(self::TABLE);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
