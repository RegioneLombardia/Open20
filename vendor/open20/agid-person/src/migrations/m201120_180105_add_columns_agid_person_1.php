<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\person\migrations
 * @category   CategoryName
 */

use open20\agid\person\models\AgidPerson;
use yii\db\Migration;

/**
 * Class m201120_180105_add_columns_agid_person_1
 */
class m201120_180105_add_columns_agid_person_1 extends Migration
{
    private $tableName;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = AgidPerson::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'person_function', $this->text()->null()->defaultValue(null)->after('other_info'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'person_function');
        return true;
    }
}
