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
 * Class m190222_084844_add_ticket_partnership_type
 */
class m190222_084844_add_ticket_partnership_type extends Migration
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
        $this->addColumn($this->tableName, 'partnership_type', $this->string(255)->null()->defaultValue(null)->after('forward_notify')->comment('Partnership Type'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'partnership_type');
        return true;
    }
}
