<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement
 * @category   CategoryName
 */
class m181115_124201_update_template extends \yii\db\Migration {

    public function safeUp() {


        $this->insert('site_management_fields_type', ['id'=> 18, 'name' => 'text_editor', 'type' => 'html']);
        $this->insert('site_management_template_fields', ['template_id' => 7, 'field_id' => 18]);
        $this->delete('site_management_template_fields', ['template_id' => 7, 'field_id' => 1]);
        $this->delete('site_management_template_fields', ['template_id' => 7, 'field_id' => 2]);

    }

    public function safeDown() {
        $this->delete('site_management_fields_type', ['id'=> 18, 'name' => 'text_editor', 'type' => 'editor']);
        $this->delete('site_management_template_fields', ['id' => 18, 'template_id' => 7, 'field_id' => 18]);
    }

}
