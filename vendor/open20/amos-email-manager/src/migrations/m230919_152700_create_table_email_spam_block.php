<?php

/**
 * Class m230919_create_table_email_spam_block
 */
class m230919_152700_create_table_email_spam_block extends \yii\db\Migration
{
    const TABLE = '{{%email_spam_block}}';

    /**
     * @return void
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'sender_user_id' => $this->integer()->notNull()->comment('Sender User ID'),
            'sending_date' => $this->dateTime()->notNull()->comment('Sending date'),
            'counter' => $this->integer()->notNull()->defaultValue(1)->comment('Counter'),

            'created_at' => $this->dateTime()->null()->defaultValue(null)->comment('Created at'),
            'updated_at' => $this->dateTime()->null()->defaultValue(null)->comment('Updated at'),
            'deleted_at' => $this->dateTime()->null()->defaultValue(null)->comment('Deleted at'),
            'created_by' => $this->integer(11)->null()->defaultValue(null)->comment('Created by'),
            'updated_by' => $this->integer(11)->null()->defaultValue(null)->comment('Updated by'),
            'deleted_by' => $this->integer(11)->null()->defaultValue(null)->comment('Deleted by'),
        ]);
    }

    /**
     * @return void
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}