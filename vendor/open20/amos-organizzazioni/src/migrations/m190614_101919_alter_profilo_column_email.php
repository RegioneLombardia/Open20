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
use yii\db\Migration;

/**
 * Class m190227_174839_alter_profilo_column_referente_operativo
 */
class m190614_101919_alter_profilo_column_email extends Migration
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
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'email', $this->string(255)->defaultValue(null));
        
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
}
