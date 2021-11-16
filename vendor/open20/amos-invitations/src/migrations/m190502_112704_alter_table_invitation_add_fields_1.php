<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\migrations
 * @category   CategoryName
 */

use open20\amos\invitations\models\Invitation;
use yii\db\Migration;

/**
 * Class m190502_112704_alter_table_invitation_add_fields_1
 */
class m190502_112704_alter_table_invitation_add_fields_1 extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = Invitation::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'module_name', $this->string(255)->null()->defaultValue(null)->after('send'));
        $this->addColumn($this->tableName, 'context_model_id', $this->integer()->null()->defaultValue(null)->after('module_name'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'module_name');
        $this->dropColumn($this->tableName, 'context_model_id');
        return true;
    }
}
