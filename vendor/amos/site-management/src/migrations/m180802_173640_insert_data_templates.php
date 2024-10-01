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
class m180802_173640_insert_data_templates extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('site_management_fields_type', ['id' => 1, 'name' => 'title', 'type' => 'string']);
        $this->insert('site_management_fields_type', ['id' => 2, 'name' => 'description', 'type' => 'text']);
        $this->insert('site_management_fields_type', ['id' => 3, 'name' => 'button_text', 'type' => 'string']);
        $this->insert('site_management_fields_type', ['id' => 4, 'name' => 'button_link', 'type' => 'string']);
        $this->insert('site_management_fields_type', ['id' => 5, 'name' => 'data','type' => 'date']);
        $this->insert('site_management_fields_type', ['id' => 6, 'name' => 'code','type' => 'string']);
        $this->insert('site_management_fields_type', ['id' => 7, 'name' => 'new','type' => 'string']);
        $this->insert('site_management_fields_type', ['id' => 8, 'name' => 'special','type' => 'string']);
        $this->insert('site_management_fields_type', ['id' => 9, 'name' => 'link_forward','type' => 'string']);
        $this->insert('site_management_fields_type', ['id' => 10, 'name' => 'image','type' => 'file']);



        $this->insert('site_management_template_fields', ['template_id' => 1, 'field_id' => 1]);
        $this->insert('site_management_template_fields', ['template_id' => 1, 'field_id' => 2]);
        $this->insert('site_management_template_fields', ['template_id' => 1, 'field_id' => 3]);
        $this->insert('site_management_template_fields', ['template_id' => 1, 'field_id' => 4]);
        $this->insert('site_management_template_fields', ['template_id' => 1, 'field_id' => 6]);
        $this->insert('site_management_template_fields', ['template_id' => 1, 'field_id' => 10]);


        $this->insert('site_management_template_fields', ['template_id' => 2, 'field_id' => 1]);
        $this->insert('site_management_template_fields', ['template_id' => 2, 'field_id' => 2]);
        $this->insert('site_management_template_fields', ['template_id' => 2, 'field_id' => 5]);
        $this->insert('site_management_template_fields', ['template_id' => 2, 'field_id' => 6]);
        $this->insert('site_management_template_fields', ['template_id' => 2, 'field_id' => 7]);
        $this->insert('site_management_template_fields', ['template_id' => 2, 'field_id' => 10]);


        $this->insert('site_management_template_fields', ['template_id' => 3, 'field_id' => 1]);
        $this->insert('site_management_template_fields', ['template_id' => 3, 'field_id' => 2]);
        $this->insert('site_management_template_fields', ['template_id' => 3, 'field_id' => 6]);
        $this->insert('site_management_template_fields', ['template_id' => 3, 'field_id' => 8]);
        $this->insert('site_management_template_fields', ['template_id' => 3, 'field_id' => 9]);
        $this->insert('site_management_template_fields', ['template_id' => 3, 'field_id' => 10]);

        $this->insert('site_management_template_fields', ['template_id' => 5, 'field_id' => 1]);
        $this->insert('site_management_template_fields', ['template_id' => 5, 'field_id' => 2]);
        $this->insert('site_management_template_fields', ['template_id' => 5, 'field_id' => 5]);
        $this->insert('site_management_template_fields', ['template_id' => 5, 'field_id' => 9]);
        $this->insert('site_management_template_fields', ['template_id' => 5, 'field_id' => 10]);


        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->delete('site_management_fields_type', ['id' => 1, 'name' => 'title', 'type' => 'string']);
        $this->delete('site_management_fields_type', ['id' => 2, 'name' => 'description', 'type' => 'text']);
        $this->delete('site_management_fields_type', ['id' => 3, 'name' => 'button_text', 'type' => 'string']);
        $this->delete('site_management_fields_type', ['id' => 4, 'name' => 'button_link', 'type' => 'string']);
        $this->delete('site_management_fields_type', ['id' => 5, 'name' => 'data','type' => 'date']);
        $this->delete('site_management_fields_type', ['id' => 6, 'name' => 'code','type' => 'string']);
        $this->delete('site_management_fields_type', ['id' => 7, 'name' => 'new','type' => 'string']);
        $this->delete('site_management_fields_type', ['id' => 8, 'name' => 'special','type' => 'string']);
        $this->delete('site_management_fields_type', ['id' => 9, 'name' => 'link_forward','type' => 'string']);
        $this->delete('site_management_fields_type', ['id' => 10, 'name' => 'image','type' => 'file']);



        $this->delete('site_management_template_fields', ['template_id' => 1, 'field_id' => 1]);
        $this->delete('site_management_template_fields', ['template_id' => 1, 'field_id' => 2]);
        $this->delete('site_management_template_fields', ['template_id' => 1, 'field_id' => 3]);
        $this->delete('site_management_template_fields', ['template_id' => 1, 'field_id' => 4]);
        $this->delete('site_management_template_fields', ['template_id' => 1, 'field_id' => 6]);
        $this->delete('site_management_template_fields', ['template_id' => 1, 'field_id' => 10]);


        $this->delete('site_management_template_fields', ['template_id' => 2, 'field_id' => 1]);
        $this->delete('site_management_template_fields', ['template_id' => 2, 'field_id' => 2]);
        $this->delete('site_management_template_fields', ['template_id' => 2, 'field_id' => 5]);
        $this->delete('site_management_template_fields', ['template_id' => 2, 'field_id' => 6]);
        $this->delete('site_management_template_fields', ['template_id' => 2, 'field_id' => 7]);
        $this->delete('site_management_template_fields', ['template_id' => 2, 'field_id' => 10]);


        $this->delete('site_management_template_fields', ['template_id' => 3, 'field_id' => 1]);
        $this->delete('site_management_template_fields', ['template_id' => 3, 'field_id' => 2]);
        $this->delete('site_management_template_fields', ['template_id' => 3, 'field_id' => 6]);
        $this->delete('site_management_template_fields', ['template_id' => 3, 'field_id' => 8]);
        $this->delete('site_management_template_fields', ['template_id' => 3, 'field_id' => 9]);
        $this->delete('site_management_template_fields', ['template_id' => 3, 'field_id' => 10]);

        $this->delete('site_management_template_fields', ['template_id' => 5, 'field_id' => 1]);
        $this->delete('site_management_template_fields', ['template_id' => 5, 'field_id' => 2]);
        $this->delete('site_management_template_fields', ['template_id' => 5, 'field_id' => 5]);
        $this->delete('site_management_template_fields', ['template_id' => 5, 'field_id' => 9]);
        $this->delete('site_management_template_fields', ['template_id' => 5, 'field_id' => 10]);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        return true;
    }
}
