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
class m180829_093640_insert_add_column_element_limit extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('site_management_container', 'element_limit', $this->integer()->comment('Limit')->after('is_masonry'));
        $this->addColumn('site_management_container', 'element_random', $this->integer()->comment('Random')->defaultValue(1)->after('is_masonry'));
        $this->alterColumn('site_manage_cont_elem_pubblication', 'end_time',  $this->time()->comment('End time'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropColumn('site_management_container', 'element_limit');
        $this->dropColumn('site_management_container', 'element_random');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        return true;
    }
}
