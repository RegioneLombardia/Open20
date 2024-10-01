<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\ProfiloSedi;
use yii\db\Migration;

/**
 * Class m190322_154720_alter_table_profilo_sedi_add_fields_address_old_style
 */
class m190322_154720_alter_table_profilo_sedi_add_fields_address_old_style extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = ProfiloSedi::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'address_text', $this->string(255)->defaultValue(null)->after('pec'));
        $this->addColumn($this->tableName, 'cap_text', $this->string(10)->defaultValue(null)->after('address_text'));
        $this->addColumn($this->tableName, 'country_id', $this->integer()->null()->defaultValue(null)->after('profilo_sedi_type_id'));
        $this->addColumn($this->tableName, 'province_id', $this->integer()->null()->defaultValue(null)->after('country_id'));
        $this->addColumn($this->tableName, 'city_id', $this->integer()->null()->defaultValue(null)->after('province_id'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'address_text');
        $this->dropColumn($this->tableName, 'cap_text');
        $this->dropColumn($this->tableName, 'country_id');
        $this->dropColumn($this->tableName, 'province_id');
        $this->dropColumn($this->tableName, 'city_id');
        return true;
    }
}
