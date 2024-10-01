<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement
 * @category   CategoryName
 */
class m190312_100401_remove_not_null_on_slider extends \yii\db\Migration {

    public function safeUp() {

        $this->alterColumn('site_management_slider','section_id', $this->integer()->defaultValue(null));
    }

    public function safeDown() {
        return true;

    }

}
