<?php

use yii\db\Migration;

/**
 * Class m190207_130342_community_user_mm_add_invitation_fields
 */
class m190207_130342_community_user_mm_add_invitation_fields extends Migration
{

    public function up()
    {
        $this->addColumn('community_user_mm', 'invited_at', $this->dateTime()->null()->defaultValue(null)->after('role'));
        $this->addColumn('community_user_mm', 'invitation_accepted_at', $this->dateTime()->null()->defaultValue(null)->after('invited_at'));
    }

    public function down()
    {
        $this->dropColumn('community_user_mm', 'invited_at');
        $this->dropColumn('community_user_mm', 'invitation_accepted_at');
    }

}
