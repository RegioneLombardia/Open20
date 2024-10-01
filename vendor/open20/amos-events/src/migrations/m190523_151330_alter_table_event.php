<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m190523_151330_alter_table_event
 */
class m190523_151330_alter_table_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Event::tableName(), 'numero_max_accompagnatori', $this->integer()->notNull()->defaultValue(5)->comment('Numero massimo di accompagnatori')->after('abilita_codice_fiscale_in_form'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Event::tableName(), 'numero_max_accompagnatori');
    }

}
