<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m171214_162104_add_documenti_fields_2
 */
class m171214_162104_add_documenti_fields_2 extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(Documenti::tableName(), 'version_parent_id', $this->integer()->null()->defaultValue(null)->after('version'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(Documenti::tableName(), 'version_parent_id');
    }
}
