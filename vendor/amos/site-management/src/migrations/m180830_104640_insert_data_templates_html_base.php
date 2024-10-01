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
class m180830_104640_insert_data_templates_html_base extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('site_management_template', ['view_path' => '@vendor/amos/site-management/src/templates/html_base'], ['id' => 4] );
        $this->insert('site_management_fields_type', ['id' => 11, 'name' => 'html_code', 'type' => 'html']);
        $this->insert('site_management_template_fields', ['template_id' => 4, 'field_id' => 11]);



        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->update('site_management_template', ['view_path' => null], ['id' => 4]);
        $this->delete('site_management_fields_type', ['id' => 11, 'name' => 'html_code', 'type' => 'html']);
        $this->delete('site_management_template_fields', ['template_id' => 4, 'field_id' => 11]);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        return true;
    }
}
