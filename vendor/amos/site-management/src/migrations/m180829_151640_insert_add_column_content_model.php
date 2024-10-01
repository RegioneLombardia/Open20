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
class m180829_151640_insert_add_column_content_model extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('site_management_template', 'content_model', $this->string()->comment('Content model')->after('view_path'));
        $this->addColumn('site_management_element', 'content_model_id', $this->integer()->comment('Content model id')->after('description'));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropColumn('site_management_template', 'content_model');
        $this->dropColumn('site_management_element', 'content_model_id');


        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        return true;
    }
}
