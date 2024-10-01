<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\Profilo;
use yii\db\Migration;

/**
 * Class m180612_163239_add_organizzazioni_profilo_field_parent_id
 */
class m180612_163239_add_organizzazioni_profilo_field_parent_id extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(Profilo::tableName(), 'parent_id', $this->integer()->null()->defaultValue(null)->after('referente_operativo'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(Profilo::tableName(), 'parent_id');
    }
}
