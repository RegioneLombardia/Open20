<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m190523_111730_alter_table_event
 */
class m190523_111730_alter_table_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Event::tableName(), 'abilita_codice_fiscale_in_form', $this->boolean()->notNull()->defaultValue(0)->comment('Abilitazione campo codice fiscale in form iscrizione evento')->after('has_qr_code'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Event::tableName(), 'abilita_codice_fiscale_in_form');
    }

}
