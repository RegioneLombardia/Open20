<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\ProfiloTypesPmi;
use yii\db\Migration;

/**
 * Class m210721_104729_add_profilo_types_pmi_field_code
 */
class m210721_104729_add_profilo_types_pmi_field_code extends Migration
{
    private $tableName;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->tableName = ProfiloTypesPmi::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'code', $this->string(20)->null()->defaultValue(null)->after('name'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'code');
        return true;
    }
}
