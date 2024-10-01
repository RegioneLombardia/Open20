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

class m221220_190033_add_column_elem_slider_2 extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {

        $this->addColumn('site_management_slider_elem', 'target', $this->integer()->defaultValue(0)->comment('Target')->after('btn_label'));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {

        $this->dropColumn('site_management_slider_elem', 'target');

        return true;
    }

}
