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

class m221220_180033_add_column_elem_slider extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('site_management_slider_elem', 'cta_label', $this->string()->null()->comment('CTA Label')->after('order'));
        $this->addColumn('site_management_slider_elem', 'category', $this->string()->null()->comment('Category')->after('cta_label'));
        $this->addColumn('site_management_slider_elem', 'btn_icon', $this->string()->null()->comment('Btn Icon')->after('category'));
        $this->addColumn('site_management_slider_elem', 'btn_label', $this->string()->null()->comment('Btn Label')->after('btn_icon'));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {

        $this->dropColumn('site_management_slider_elem', 'cta_label');
        $this->dropColumn('site_management_slider_elem', 'category');
        $this->dropColumn('site_management_slider_elem', 'btn_icon');
        $this->dropColumn('site_management_slider_elem', 'btn_label');

        return true;
    }

}
