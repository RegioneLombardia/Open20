<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\events\models\EventType;
use yii\db\Migration;

/**
 * Class m201116_173415_add_columns_event_6
 */
class m211027_113700_add_columns_event_type extends Migration
{
    private $tableName;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = EventType::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'type_icon', $this->string()->null()->defaultValue('seat')->comment('Icona tipo evento')->after('logoRequested'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'type_icon');
        return true;
    }
}
