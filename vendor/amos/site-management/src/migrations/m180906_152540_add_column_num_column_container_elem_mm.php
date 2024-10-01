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
class m180906_152540_add_column_num_column_container_elem_mm extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('site_management_container_element_mm', 'elem_order', $this->integer()->comment('Order')->after('element_id'));
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn('site_management_container_element_mm', 'elem_order');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        return true;
    }
}
