<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Undocumented class
 */
class m201222_095000_add_column_to_template extends Migration
{

    public function up()
    {
        $this->addColumn('preference_mailup_template', 'path', $this->string(255)->null()->defaultValue(null)->after('title'));

        $this->update('preference_mailup_template', ['path' => '@backend/modules/campains/mailup_templates/1_spazioregione_informa'], ['id' => '1']);
        $this->update('preference_mailup_template', ['path' => '@backend/modules/campains/mailup_templates/2_agrifolium'], ['id' => '2']);
        $this->update('preference_mailup_template', ['path' => '@backend/modules/campains/mailup_templates/3_lombardia_informa'], ['id' => '3']);
        $this->update('preference_mailup_template', ['path' => '@backend/modules/campains/mailup_templates/4_lombardia_informa_con_loghi'], ['id' => '4']);
        $this->update('preference_mailup_template', ['path' => '@backend/modules/campains/mailup_templates/5_lombardia_informa_senza_loghi'], ['id' => '5']);
    
        $this->dropColumn('preference_mailup_template', 'section');
        $this->dropColumn('preference_mailup_template', 'content_center_image');
        $this->dropColumn('preference_mailup_template', 'content_no_image');
        $this->dropColumn('preference_mailup_template', 'content_left_image');
        $this->dropColumn('preference_mailup_template', 'content_right_image');
        $this->dropColumn('preference_mailup_template', 'separator');
    }

    public function down()
    {
        $this->dropColumn('preference_mailup_template', 'path');
    }

}