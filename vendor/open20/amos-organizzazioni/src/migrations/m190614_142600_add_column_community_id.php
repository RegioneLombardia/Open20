<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    aster\platform\common\console\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\Profilo;
use yii\db\Migration;

/**
 * Class m190509_173500_add_column_priority_rows_profilo_enti_type
 */
class m190614_142600_add_column_community_id extends Migration
{
    private 
        $tableName = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = Profilo::tableName();
    }

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'community_id', $this->integer()->after('profilo_enti_type_id')->defaultValue(null)->comment('Community ID'));

        return true;
    }

    public function safeDown()
    {
        return true;
    }
}