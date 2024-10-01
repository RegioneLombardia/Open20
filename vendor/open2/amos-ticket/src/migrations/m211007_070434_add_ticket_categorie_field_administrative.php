<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open2\amos\ticket\models\TicketCategorie;
use yii\db\Migration;

/**
 * Class m211007_070434_add_ticket_categorie_field_administrative
 */
class m211007_070434_add_ticket_categorie_field_administrative extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(TicketCategorie::tableName(), 'administrative', $this->boolean()->null()->defaultValue(0)->after('tecnica'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(TicketCategorie::tableName(), 'administrative');
        return true;
    }
}
