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
class m180927_160640_add_column_container extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('site_management_container', 'fixed_template_id', $this->integer()->comment('Template')->after('description')) ;
        $this->addForeignKey('fk_sm_container_fixed_template_id1','site_management_container', 'fixed_template_id', 'site_management_template', 'id');

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn('site_management_template', 'fixed_template_id');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        return true;
    }
}
