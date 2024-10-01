<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use yii\db\TableSchema;

/**
 * Class m231220_113800_add_columns_alt_news
 */
class m240304_114130_add_columns_alt_news extends \yii\db\Migration
{
    const NEWS = 'news';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        /** @var TableSchema $tableSchema */
        $tableSchema = $this->db->schema->getTableSchema(self::NEWS, true);
        if (!is_null($tableSchema)) {
            if (!in_array('alt',$tableSchema->columnNames)) {
                $this->addColumn(self::NEWS, 'alt', $this->string(100)->comment('Alt'));
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // do nothing
        return true;
    }

}