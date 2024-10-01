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
class m181113_104440_add_column_container extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('site_management_container', 'page_name', $this->string()->comment('Text position')->after('id')) ;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn('site_management_container', 'page_name') ;
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        return true;
    }
}
