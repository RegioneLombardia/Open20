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
 * Class m171206_092631_add_documenti_fields_1
 */
class m171206_092631_add_documenti_fields_1 extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        /*$this->addColumn(Documenti::tableName(), 'parent_id', $this->integer()->null()->defaultValue(null)->after('comments_enabled'));
        $this->addColumn(Documenti::tableName(), 'is_folder', $this->boolean()->notNull()->defaultValue(0)->after('parent_id'));
        $this->addColumn(Documenti::tableName(), 'version', $this->integer()->null()->defaultValue(null)->after('is_folder'));*/
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        /*$this->dropColumn(Documenti::tableName(), 'parent_id');
        $this->dropColumn(Documenti::tableName(), 'is_folder');
        $this->dropColumn(Documenti::tableName(), 'version');*/
        return true;
    }
}
