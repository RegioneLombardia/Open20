<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement
 * @category   CategoryName
 */
class m190321_120401_alter_content_col extends \yii\db\Migration
{

    public function safeUp()
    {

        $this->alterColumn('site_management_page_content', 'content', ' MEDIUMTEXT NULL DEFAULT NULL COMMENT "Content"');
    }

    public function safeDown()
    {
        return true;
    }
}