<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m180308_094940_add_foreign_keys_site_management_metadata
 */
class m180308_094940_add_foreign_keys_site_management_metadata extends Migration
{
    private $tableName = 'site_management_metadata';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addForeignKey('fk_metadata_metadata_type', $this->tableName, 'metadata_type_id', 'site_management_metadata_type', 'id');
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_metadata_metadata_type', $this->tableName);
        return true;
    }
}
