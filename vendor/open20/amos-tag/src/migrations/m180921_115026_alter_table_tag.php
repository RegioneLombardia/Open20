<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

use open20\amos\tag\models\Tag;
use yii\db\Migration;

/**
 * Class m180921_115026_alter_table_tag
 */
class m180921_115026_alter_table_tag extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->addColumn(Tag::tableName(), 'child_allowed', $this->boolean()->notNull()->defaultValue(true));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(Tag::tableName(), 'child_allowed');
    }
}
