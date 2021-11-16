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
 * Class m201115_002900_add_fk_site_management_slider_to_news
 */
class m201115_002900_add_fk_site_management_slider_to_news extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('news', 'image_site_management_slider_id', $this->integer());
        $this->addColumn('news', 'video_site_management_slider_id', $this->integer());
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // Drop Column
        $this->dropColumn('news', 'image_site_management_slider_id');
        $this->dropColumn('news', 'video_site_management_slider_id');
    }
}
