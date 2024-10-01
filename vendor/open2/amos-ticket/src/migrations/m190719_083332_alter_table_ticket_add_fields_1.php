<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open2\amos\ticket\models\Ticket;
use yii\db\Migration;

/**
 * Class m190719_083332_alter_table_ticket_add_fields_1
 */
class m190719_083332_alter_table_ticket_add_fields_1 extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = Ticket::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'dossier_id', $this->string(50)->null()->defaultValue(null)->after('organization_name'));
        $this->addColumn($this->tableName, 'phone', $this->string(50)->null()->defaultValue(null)->after('dossier_id'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'dossier_id');
        $this->dropColumn($this->tableName, 'phone');
        return true;
    }
}
