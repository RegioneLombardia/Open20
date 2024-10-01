<?php

use yii\db\Migration;
use yii\db\Schema;
use open20\amos\sondaggi\models\SondaggiInvitations;

/**
 * Class m210521_145731_add_fields_sondaggi_invitations
 */
class m210521_145731_add_fields_sondaggi_invitations extends Migration
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
        $this->addColumn($this->tableName, 'type', $this->tinyInteger()->notNull()->after('name'));
        $this->addColumn($this->tableName, 'filter_type', $this->tinyInteger()->notNull()->after('type'));
        $this->addColumn($this->tableName, 'search_groups', $this->text()->null()->after('filter_type'));
        $this->addColumn($this->tableName, 'search_tags', $this->text()->null()->after('search_groups'));
        $this->addColumn($this->tableName, 'field', $this->text()->null()->after('search_tags'));
        $this->addColumn($this->tableName, 'value', $this->text()->null()->after('field'));
        $this->addColumn($this->tableName, 'include_exclude', $this->text()->null()->after('value'));
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'type');
        $this->dropColumn($this->tableName, 'filter_type');
        $this->dropColumn($this->tableName, 'search_groups');
        $this->dropColumn($this->tableName, 'search_tags');
        $this->dropColumn($this->tableName, 'field');
        $this->dropColumn($this->tableName, 'value');
        $this->dropColumn($this->tableName, 'include_exclude');
        return true;
    }
}