<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement
 * @category   CategoryName
 */
class m190730_115001_add_column_permission extends \yii\db\Migration
{

    public function safeUp()
    {
        $this->addColumn('site_management_slider','permission', $this->string()->after('view_path'));
        $this->addColumn('site_management_container','permission', $this->string()->after('fixed_template_id'));
        $this->addColumn('site_management_page_content','permission', $this->string()->after('content'));
    }

    public function safeDown()
    {
        $this->dropColumn('site_management_slider','permission');
        $this->dropColumn('site_management_container','permission');
        $this->dropColumn('site_management_page_content','permission');


    }
}