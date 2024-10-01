<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\ProfiloTypesPmi;
use yii\db\Migration;

/**
 * Class m190201_163720_add_profilo_field
 */
class m190201_163720_add_profilo_field extends Migration
{
    private $tableName;
    private $fieldName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = ProfiloTypesPmi::tableName();
        $this->fieldName = 'type_cat';
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, $this->fieldName, $this->integer()->notNull()->defaultValue(0)->comment('Type Cat'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, $this->fieldName);
        return true;
    }
}
