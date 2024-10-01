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
 * Class m200401_135026_add_ticket_categorie_field_technical_assistance_description
 */
class m200401_135026_add_ticket_categorie_field_technical_assistance_description extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = TicketCategorie::tableName();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'technical_assistance_description', $this->text()->defaultValue(null)->after('enable_phone'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'technical_assistance_description');
        return true;
    }
}
