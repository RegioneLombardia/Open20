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
 * Class m170315_104802_user_community_mm_add_status_and_role_fields
 */
class m170315_104802_user_community_mm_add_status_and_role_fields extends Migration
{
    const COMMUNITY_USER_MM = 'community_user_mm';
    const STATUS = 'status';
    const ROLE = 'role';
    const COLUMN_STATUS_TYPE = "VARCHAR(100) AFTER user_id " ;
    const COLUMN_ROLE_TYPE = "VARCHAR(100) AFTER status " ;

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::COMMUNITY_USER_MM, self::STATUS, self::COLUMN_STATUS_TYPE);
        $this->addColumn(self::COMMUNITY_USER_MM, self::ROLE, self::COLUMN_ROLE_TYPE);
        $this->update(self::COMMUNITY_USER_MM, [self::STATUS => 'ACTIVE']);
        $this->update(self::COMMUNITY_USER_MM, [self::ROLE => 'COMMUNITY_MANAGER']);

        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::COMMUNITY_USER_MM, self::ROLE);
        $this->dropColumn(self::COMMUNITY_USER_MM, self::STATUS);
        return true;
    }
}
