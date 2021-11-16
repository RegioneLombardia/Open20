<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m170502_132621_add_parent_id_field_to_community
 */
class m170502_132621_add_parent_id_field_to_community extends Migration
{
    const COMMUNITY = 'community';
    const PARENT_ID_FIELD = 'parent_id';
    const ID_COLUMN_TYPE = 'INT(11)';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::COMMUNITY, self::PARENT_ID_FIELD, self::ID_COLUMN_TYPE);

    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::COMMUNITY, self::PARENT_ID_FIELD);
        return true;
    }
}
