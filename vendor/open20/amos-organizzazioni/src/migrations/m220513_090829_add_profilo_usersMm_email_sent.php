<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\ProfiloUserMm;
use yii\db\Migration;

/**
 * Class m210721_104729_add_profilo_types_pmi_field_code
 */
class m220513_090829_add_profilo_usersMm_email_sent extends Migration
{
    private $tableName;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->tableName = ProfiloUserMm::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'email_sent', $this->boolean()->notNull()->defaultValue(0)->after('status'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'email_sent');
        return true;
    }
}
