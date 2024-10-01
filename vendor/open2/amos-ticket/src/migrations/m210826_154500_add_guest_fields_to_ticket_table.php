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
 * Class m210826_154500_add_guest_fields_to_ticket_table
 */
class m210826_154500_add_guest_fields_to_ticket_table extends Migration
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
        $this->addColumn($this->tableName, 'guest_name', $this->string(255)->defaultValue(null)->after('phone'));
        $this->addColumn($this->tableName, 'guest_surname', $this->string(255)->defaultValue(null)->after('guest_name'));
        $this->addColumn($this->tableName, 'guest_email', $this->string(255)->defaultValue(null)->after('guest_surname'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'guest_name');
        $this->dropColumn($this->tableName, 'guest_surname');
        $this->dropColumn($this->tableName, 'guest_email');
        return true;
    }

}
