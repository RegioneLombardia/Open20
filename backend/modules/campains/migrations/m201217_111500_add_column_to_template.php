<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */

use yii\db\Migration;


class m201217_111500_add_column_to_template extends Migration
{

    public function up()
    {
        // addColumn
        $this->addColumn('preference_mailup_template', 'aspect_ratio', $this->text()->null()->defaultValue(null)->after('colors_choice'));
        $this->addColumn('preference_mailup_template', 'aspect_ratio_choices', $this->text()->null()->defaultValue(null)->after('aspect_ratio'));
        // [{"label":"X","value":"x","title":"Elimina il crop"},{"label":"16:9","value":1.7777777777778,"title":"Fattore di crop 16:9"}]

        $this->update('preference_mailup_template', 
            ['aspect_ratio_choices' => '[{"label":"X","value":"x","title":"Elimina il crop"},{"label":"16:9","value":1.7777777777778,"title":"Fattore di crop 16:9"}]']
            , ['>', 'id', '0']);

        $this->update('preference_mailup_template', 
            ['aspect_ratio' => '1.7777777777778']
            , ['>', 'id', '0']);

    }

    public function down()
    {
        // dropColumn
        $this->dropColumn('preference_mailup_template', 'aspect_ratio');
        $this->dropColumn('preference_mailup_template', 'aspect_ratio_choices');
    }

}