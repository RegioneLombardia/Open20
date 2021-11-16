<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\community\models\Community;
use yii\db\Migration;

/**
 * Class m190530_145215_add_community_forceworkflow
 */
class m190530_145215_add_community_forceworkflow extends Migration
{
    private $tableName;
    private $fieldName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = Community::tableName();
        $this->fieldName = 'force_workflow';
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, $this->fieldName, $this->boolean()->notNull()->defaultValue(0)->comment('Force Workflow'));

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
