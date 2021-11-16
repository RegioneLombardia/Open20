<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m190122_154813_newsletter_service_mail_fields_mm extends Migration
{
    const TABLE_NEWSLETTER_SERVICE_FIELD = "newsletter_service_mail_fields_mm";






    /**
     * @inheritdoc
     */
    public function up()
    {


        if ($this->db->schema->getTableSchema(self::TABLE_NEWSLETTER_SERVICE_FIELD, true) === null)
        {
            $this->createTable(self::TABLE_NEWSLETTER_SERVICE_FIELD, [
                'id' => Schema::TYPE_PK,
                'newsletter_id' => $this->integer()->notNull()->comment('Newsletter'),
                'service_mail_field_id' => $this->integer()->notNull()->comment('Service mail Field'),
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


    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable(self::TABLE_NEWSLETTER_SERVICE_FIELD);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
