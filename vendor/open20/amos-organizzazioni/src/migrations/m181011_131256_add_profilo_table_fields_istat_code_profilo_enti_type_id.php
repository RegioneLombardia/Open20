<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloEntiType;
use yii\db\Migration;

/**
 * Class m181011_131256_add_profilo_table_fields_istat_code_profilo_enti_type_id
 */
class m181011_131256_add_profilo_table_fields_istat_code_profilo_enti_type_id extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = Profilo::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'istat_code', $this->string(10)->null()->defaultValue(null)->after('codice_fiscale')->comment('Istat Code'));
        $this->addColumn($this->tableName, 'profilo_enti_type_id', $this->integer()->notNull()->defaultValue(ProfiloEntiType::TYPE_OTHER_ENTITY)->after('parent_id')->comment('Profilo Enti Type Id'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'istat_code');
        $this->dropColumn($this->tableName, 'profilo_enti_type_id');
        return true;
    }
}
