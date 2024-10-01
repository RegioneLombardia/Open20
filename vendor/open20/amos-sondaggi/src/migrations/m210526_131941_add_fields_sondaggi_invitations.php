<?php

use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\SondaggiInvitations;

/**
 * Class m210526_131941_add_fields_sondaggi_invitations
 */
class m210526_131941_add_fields_sondaggi_invitations extends Migration
{
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = SondaggiInvitations::tableName();
    }

    /**, 'filter_type','search_tags', 'search_groups', 'field', 'value', 'include_exclude''
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'invited', $this->tinyInteger()->notNull()->after('active'));
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'invited');
        return true;
    }
}