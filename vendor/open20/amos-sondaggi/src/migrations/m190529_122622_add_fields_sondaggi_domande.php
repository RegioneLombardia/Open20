<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190227_143522_create_sondaggi_model_content
 */
class m190529_122622_add_fields_sondaggi_domande extends Migration
{
    const TABLE = '{{%sondaggi_domande}}';
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sondaggi', 'send_pdf_via_email', $this->integer()->defaultValue(0)->after('sondaggi_stato_id'));
        $this->addColumn('sondaggi_domande', 'tooltip', $this->text()->after('domanda'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropColumn('sondaggi', 'send_pdf_via_email');
        $this->dropColumn('sondaggi_domande', 'tooltip');
    }

}
