<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m190523_175530_alter_table_event
 */
class m190523_175530_alter_table_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Event::tableName(), 'thank_you_page_view', $this->string(400)->null()->comment('Thank you page view')->after('gdpr_question_5'));
        $this->addColumn(Event::tableName(), 'subscribe_form_page_view', $this->string(400)->null()->comment('Subscribe form page view')->after('thank_you_page_view'));
        $this->addColumn(Event::tableName(), 'email_view', $this->string(400)->null()->comment('Email view')->after('subscribe_form_page_view'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Event::tableName(), 'thank_you_page_view');
        $this->dropColumn(Event::tableName(), 'subscribe_form_page_view');
        $this->dropColumn(Event::tableName(), 'email_view');
    }

}
