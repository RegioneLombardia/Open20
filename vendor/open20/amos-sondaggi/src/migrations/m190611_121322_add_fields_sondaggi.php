<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190227_143522_create_sondaggi_model_content
 */
class m190611_121322_add_fields_sondaggi extends Migration
{
    const TABLE = '{{%sondaggi}}';
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sondaggi', 'additional_emails', $this->string()->defaultValue(null)->after('send_pdf_via_email'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropColumn('sondaggi', 'additional_emails');
    }

}
