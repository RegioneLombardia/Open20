<?php

use yii\db\Migration;

/**
 * Class m190312_122051_community_user_mm_add_invitation_partner_of
 */
class m190312_122051_community_user_mm_add_invitation_partner_of extends Migration
{

    public function up()
    {
        $this->addColumn('community_user_mm', 'invitation_partner_of', $this->integer()->null()->defaultValue(null)->after('invitation_accepted_at'));
    }

    public function down()
    {
        $this->dropColumn('community_user_mm', 'invitation_partner_of');
    }

}
