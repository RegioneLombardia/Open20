<?php

use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\SondaggiInvitations;

/**
 * Class m210525_182208_add_fields_sondaggi_invitations
 */
class m210525_182208_add_fields_sondaggi_invitations extends Migration
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
        $this->addColumn($this->tableName, 'active', $this->tinyInteger()->notNull()->after('include_exclude'));
        $this->addColumn($this->tableName, 'count', $this->tinyInteger()->notNull()->after('active'));
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'active');
        $this->dropColumn($this->tableNAme, 'count');
        return true;
    }
}