<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\community\models\Community;
use yii\db\Migration;

/**
 * Class m171219_111336_add_community_field_hits
 */
class m181106_125036_modify_unique_tag_section extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropIndex('tag', 'site_management_page_content');
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('site_management_page_content', 'tag', $this->string()->unique());

    }
}
