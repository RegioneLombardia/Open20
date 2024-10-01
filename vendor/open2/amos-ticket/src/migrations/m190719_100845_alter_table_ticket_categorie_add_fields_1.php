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
 * Class m190719_100845_alter_table_ticket_categorie_add_fields_1
 */
class m190719_100845_alter_table_ticket_categorie_add_fields_1 extends Migration
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
        $this->addColumn($this->tableName, 'enable_dossier_id', $this->boolean()->notNull()->defaultValue(0)->after('abilita_per_community'));
        $this->addColumn($this->tableName, 'enable_phone', $this->boolean()->notNull()->defaultValue(0)->after('enable_dossier_id'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'enable_dossier_id');
        $this->dropColumn($this->tableName, 'enable_phone');
        return true;
    }
}
