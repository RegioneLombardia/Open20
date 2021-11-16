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
 * Class m201113_153600_add_fk_news_content_type
 */
class m201113_153600_add_fk_news_content_type extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // addColumn to agid_organization_unit
        $this->addColumn('news', 'news_content_type_id', $this->integer()->null()->defaultValue(null));
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // dropColumn
        $this->dropColumn('news', 'news_content_type_id');
    }
}
