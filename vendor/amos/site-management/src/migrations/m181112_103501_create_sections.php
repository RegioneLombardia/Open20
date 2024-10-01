<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement
 * @category   CategoryName
 */
class m181112_103501_create_sections extends \yii\db\Migration {

    public function safeUp() {


        $this->insert('site_management_template', ['id' => 7, 'name'=> 'List element', 'view_path' => '@vendor/amos/site-management/src/templates/elem_list']);
        $this->insert('site_management_template_fields', ['template_id' => 7, 'field_id' => 10]);
        $this->insert('site_management_template_fields', ['template_id' => 7, 'field_id' => 1]);
        $this->insert('site_management_template_fields', ['template_id' => 7, 'field_id' => 2]);

    }

    public function safeDown() {

        $this->insert('site_management_template', ['id' => 7, 'name'=> 'List element', 'view_path' => '@vendor/amos/site-management/src/templates/elem_list']);
        $this->insert('site_management_template_fields', ['template_id' => 7, 'field_id' => 10]);
        $this->insert('site_management_template_fields', ['template_id' => 7, 'field_id' => 1]);
        $this->insert('site_management_template_fields', ['template_id' => 7, 'field_id' => 2]);
    }

}
