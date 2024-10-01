<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement
 * @category   CategoryName
 */
class m190103_110401_update_template_new extends \yii\db\Migration {

    public function safeUp() {

        $this->insert('site_management_template_fields', ['template_id' => 2, 'field_id' => 9]);
    }

    public function safeDown() {
        $this->delete('site_management_template_fields', ['template_id' => 2, 'field_id' => 9]);

    }

}
