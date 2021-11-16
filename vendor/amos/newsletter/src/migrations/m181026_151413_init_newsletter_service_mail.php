<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m181026_151413_init_newsletter_service_mail extends Migration
{
    const TABLE_NEWSLETTER_SERVICE_EMAIL_LIST = "newsletter_service_email_list";
    const TABLE_NEWSLETTER_SERVICE_EMAIL_GROUP = "newsletter_service_email_group";
    const TABLE_NEWSLETTER_SERVICE_EMAIL_MEMBER = "newsletter_service_email_member";





    /**
     * @inheritdoc
     */
    public function up()
    {



//        if ($this->db->schema->getTableSchema(self::TABLE_NEWSLETTER_SERVICE_EMAIL_LIST, true) === null)
//        {
//            $this->createTable(self::TABLE_NEWSLETTER_SERVICE_EMAIL_LIST, [
//                'id' => Schema::TYPE_PK,
//                'description' => $this->string()->comment('Service mail'),
//                'created_at' => $this->dateTime()->comment('Created at'),
//                'updated_at' =>  $this->dateTime()->comment('Updated at'),
//                'deleted_at' => $this->dateTime()->comment('Deleted at'),
//                'created_by' =>  $this->integer()->comment('Created by'),
//                'updated_by' =>  $this->integer()->comment('Updated at'),
//                'deleted_by' =>  $this->integer()->comment('Deleted at'),
//            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
//        }
//        else
//        {
//            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
//        }
//
//        if ($this->db->schema->getTableSchema(self::TABLE_NEWSLETTER_SERVICE_EMAIL_GROUP, true) === null)
//        {
//            $this->createTable(self::TABLE_NEWSLETTER_SERVICE_EMAIL_GROUP, [
//                'id' => Schema::TYPE_PK,
//                'list_id' => $this->integer()->comment('Service mail'),
//                'description' => $this->string()->comment('Service mail'),
//                'created_at' => $this->dateTime()->comment('Created at'),
//                'updated_at' =>  $this->dateTime()->comment('Updated at'),
//                'deleted_at' => $this->dateTime()->comment('Deleted at'),
//                'created_by' =>  $this->integer()->comment('Created by'),
//                'updated_by' =>  $this->integer()->comment('Updated at'),
//                'deleted_by' =>  $this->integer()->comment('Deleted at'),
//            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
//            $this->addForeignKey('fk_newsletter_semail_list_id1', self::TABLE_NEWSLETTER_SERVICE_EMAIL_GROUP, 'list_id', 'newsletter_service_email_list', 'id');
//        }
//        else
//        {
//            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
//        }

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
//        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
//        $this->dropTable(self::TABLE_NEWSLETTER_SERVICE_EMAIL_GROUP);
//        $this->dropTable(self::TABLE_NEWSLETTER_SERVICE_EMAIL_LIST);
//        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
