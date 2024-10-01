<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

class m220824_150000_add_column_instagram_video_caption extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('site_management_slider_elem', 'instagram_video_caption', $this->boolean()->notNull()->defaultValue(0)->comment('Instagram video caption')->after('instagram_url_video')) ;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropColumn('site_management_slider_elem', 'instagram_video_caption');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        return true;
    }
}
