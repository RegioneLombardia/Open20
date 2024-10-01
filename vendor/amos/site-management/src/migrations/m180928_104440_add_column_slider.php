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

/**
 * Class m180308_094940_add_foreign_keys_site_management_metadata
 */
class m180928_104440_add_column_slider extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('site_management_slider_elem', 'text_position', $this->integer()->comment('Text position')->after('url_video')) ;
        $this->addColumn('site_management_slider_elem', 'path_video', $this->string()->comment('Path video')->after('url_video')) ;
        $this->addColumn('site_management_slider_elem', 'link', $this->string()->comment('Link')->after('description')) ;



        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn('site_management_slider_elem', 'text_position') ;
        $this->dropColumn('site_management_slider_elem', 'path_video');
        $this->dropColumn('site_management_slider_elem', 'link') ;

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        return true;
    }
}
