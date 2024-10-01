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
class m190513_124844_add_organization_name extends Migration
{
    public $tableName = 'ticket';


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'organization_name', $this->string(255)->defaultValue(null)->after('partnership_id')->comment('Organization name'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'organization_name');
        return true;
    }
}
