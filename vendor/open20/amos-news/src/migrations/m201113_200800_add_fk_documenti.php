<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m201113_200800_add_fk_documenti
 */
class m201113_200800_add_fk_documenti extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // addColumn 
        $this->addColumn('news', 'news_documento_id', $this->integer()->null()->defaultValue(null));
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // dropColumn
        $this->dropColumn('news', 'news_documento_id');
    }
}
