<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m160912_145023_alter_workflow_tables_discussioni
 */
class m160912_145023_alter_workflow_tables_discussioni extends Migration
{
    const TABLE_WORKFLOW = '{{%sw_workflow}}';
    const TABLE_WORKFLOW_STATUS = '{{%sw_status}}';
    const TABLE_WORKFLOW_TRANSITIONS = '{{%sw_transition}}';
    const TABLE_WORKFLOW_METADATA = '{{%sw_metadata}}';
    const DEFAULT_CHARSET = ", CHARACTER SET utf8 COLLATE utf8_unicode_ci";
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
        
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->alterColumn(self::TABLE_WORKFLOW, 'id', $this->string(255)->notNull()->append(self::DEFAULT_CHARSET));
        $this->alterColumn(self::TABLE_WORKFLOW, 'initial_status_id', $this->string(255)->null()->append(self::DEFAULT_CHARSET));
        
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_STATUS . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->alterColumn(self::TABLE_WORKFLOW_STATUS, 'id', $this->string(255)->notNull()->append(self::DEFAULT_CHARSET));
        $this->alterColumn(self::TABLE_WORKFLOW_STATUS, 'workflow_id', $this->string(255)->notNull()->append(self::DEFAULT_CHARSET));
        $this->alterColumn(self::TABLE_WORKFLOW_STATUS, 'label', $this->string(255)->null()->append(self::DEFAULT_CHARSET));
        
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_TRANSITIONS . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->alterColumn(self::TABLE_WORKFLOW_TRANSITIONS, 'workflow_id', $this->string(255)->notNull()->append(self::DEFAULT_CHARSET));
        $this->alterColumn(self::TABLE_WORKFLOW_TRANSITIONS, 'start_status_id', $this->string(255)->notNull()->append(self::DEFAULT_CHARSET));
        $this->alterColumn(self::TABLE_WORKFLOW_TRANSITIONS, 'end_status_id', $this->string(255)->notNull()->append(self::DEFAULT_CHARSET));
        
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_METADATA . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->alterColumn(self::TABLE_WORKFLOW_METADATA, 'workflow_id', $this->string(255)->notNull()->append(self::DEFAULT_CHARSET));
        $this->alterColumn(self::TABLE_WORKFLOW_METADATA, 'status_id', $this->string(255)->notNull()->append(self::DEFAULT_CHARSET));
        $this->alterColumn(self::TABLE_WORKFLOW_METADATA, '`key`', $this->string(255)->notNull()->append(self::DEFAULT_CHARSET));
        $this->alterColumn(self::TABLE_WORKFLOW_METADATA, 'value', $this->string(255)->null()->append(self::DEFAULT_CHARSET));
        
        $this->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
        
        return true;
    }
    
    /**
     * Use this instead of function down().
     */
    public function safeDown()
    {
        echo "Reverting alter to workflow tables is not expected.\n";
        return true;
    }
}
