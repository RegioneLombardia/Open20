<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210519_160441_rename_invitation_columns
 */
class m210519_160441_rename_invitation_columns extends Migration
{
    const TABLE_INVITATION = '{{%sondaggi_invitation_mm}}';
    const TABLE_USERS = '{{%sondaggi_users_invitation_mm}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn(self::TABLE_INVITATION, 'invitation_id', 'to_id');
        $this->addColumn(self::TABLE_INVITATION, 'invitation_id', $this->integer()->notNull()->after('sondaggi_id'));
        $this->renameColumn(self::TABLE_USERS, 'invitation_id', 'to_id');
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn(self::TABLE_USERS, 'to_id', 'invitation_id');
        $this->dropColumn(self::TABLE_INVITATION, 'invitation_id');
        $this->renameColumn(self::TABLE_INVITATION, 'to_id', 'invitation_id');
        return true;
    }
}