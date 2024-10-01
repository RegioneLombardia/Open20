<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\migrations
 * @category   CategoryName
 */

use open20\amos\sondaggi\models\Sondaggi;
use yii\db\Migration;

/**
 * Class m180907_131126_alter_sondaggi_table_add_status_column
 */
class m180907_131126_alter_sondaggi_table_add_status_column extends Migration
{
    private $tableName;
    private $fieldNameName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = Sondaggi::tableName();
        $this->fieldNameName = 'status';
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, $this->fieldNameName, $this->string(255)->null()->defaultValue(null)->after('id')->comment('Status'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, $this->fieldNameName);
        return true;
    }
}
