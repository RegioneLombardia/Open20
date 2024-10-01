<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    aster\platform\common\console\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\base\ProfiloEntiType;
use yii\db\Migration;

/**
 * Class m190509_173500_add_column_priority_rows_profilo_enti_type
 */
class m190509_173500_add_column_priority_rows_profilo_enti_type extends Migration
{
    private $tableName = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = ProfiloEntiType::tableName();
    }

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'priority', $this->integer()->after('name')->defaultValue(0)->comment('Order priority inside select html'));

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'priority');

        return true;
    }
}