<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

class m181221_102001_drop_unique_table extends Migration
{
    public function safeUp()
    {
        $this->dropIndex('idx-classname-content_id','seo_data');
        $this->dropIndex('pretty_url','seo_data');
        $this->createIndex(
            'idx-classname-content_id',
            'seo_data',
            'classname,content_id'
        );

    }

    public function safeDown()
    {
        return true;
//        $this->createIndex(
//            'idx-classname-content_id',
//            'seo_data',
//            'classname,content_id',
//            true //unique
//        );
    }
}