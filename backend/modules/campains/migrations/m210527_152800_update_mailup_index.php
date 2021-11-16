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


class m210527_152800_update_mailup_index extends Migration
{

    public function up()
    {
        $this->update('preference_mailup_template', ['mailup_template_id' => 1, 'mailup_template_preview_id' => 2], ['id' => 4]);
        $this->update('preference_mailup_template', ['mailup_template_id' => 3, 'mailup_template_preview_id' => 4], ['id' => 5]);
    }

    public function down()
    {
        $this->update('preference_mailup_template', ['mailup_template_id' => 1100, 'mailup_template_preview_id' => 1095], ['id' => 4]);
        $this->update('preference_mailup_template', ['mailup_template_id' => 1102, 'mailup_template_preview_id' => 1094], ['id' => 5]);

    }

}