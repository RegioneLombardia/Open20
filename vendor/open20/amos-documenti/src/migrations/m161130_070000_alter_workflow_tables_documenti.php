<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigration;

/**
 * Class m161130_070000_alter_workflow_tables_documenti
 */
class m161130_070000_alter_workflow_tables_documenti extends AmosMigration
{
    const TABLE_WORKFLOW = '{{%sw_workflow}}';
    const TABLE_WORKFLOW_STATUS = '{{%sw_status}}';
    const TABLE_WORKFLOW_TRANSITIONS = '{{%sw_transition}}';
    const TABLE_WORKFLOW_METADATA = '{{%sw_metadata}}';

    /**
     * Use this instead of function up().
     */
    public function safeUp()
    {
        $this->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();

        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW . " CHANGE COLUMN id id VARCHAR(255) NOT NULL, CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW . " CHANGE COLUMN initial_status_id initial_status_id VARCHAR(255), CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();

        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_STATUS . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_STATUS . " CHANGE COLUMN id id VARCHAR(255) NOT NULL, CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_STATUS . " CHANGE COLUMN workflow_id workflow_id VARCHAR(255) NOT NULL, CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_STATUS . " CHANGE COLUMN label label VARCHAR(255), CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();

        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_TRANSITIONS . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_TRANSITIONS . " CHANGE COLUMN workflow_id workflow_id VARCHAR(255) NOT NULL, CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_TRANSITIONS . " CHANGE COLUMN start_status_id start_status_id VARCHAR(255) NOT NULL, CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_TRANSITIONS . " CHANGE COLUMN end_status_id end_status_id VARCHAR(255) NOT NULL, CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();

        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_METADATA . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_METADATA . " CHANGE COLUMN workflow_id workflow_id VARCHAR(255) NOT NULL, CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_METADATA . " CHANGE COLUMN status_id status_id VARCHAR(255) NOT NULL, CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_METADATA . " CHANGE COLUMN `key` `key` VARCHAR(255) NOT NULL, CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();
        $this->db->createCommand("ALTER TABLE " . self::TABLE_WORKFLOW_METADATA . " CHANGE COLUMN value value VARCHAR(255), CHARACTER SET utf8 COLLATE utf8_unicode_ci")->execute();

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
