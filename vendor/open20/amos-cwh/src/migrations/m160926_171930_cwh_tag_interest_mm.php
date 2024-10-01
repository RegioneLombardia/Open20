<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

use yii\db\Migration;

class m160926_171930_cwh_tag_interest_mm extends Migration
{
    const TABLE = "cwh_tag_interest_mm";

    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'tag_id' => $this->integer(11)->notNull()->comment('Root'),
            'classname' => $this->string(255)->notNull()->comment('Model'),
            'auth_item' => $this->string(255)->notNull()->comment('Item'),
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);

        $this->addPrimaryKey('pk_cwh_tag_interest_mm_id', self::TABLE, 'tag_id, classname, auth_item');

        if(Yii::$app->db->schema->getTableSchema('tag') !== null) { // IF tag table exists
            $this->addForeignKey('fk_cwh_tag_interest_mm_tag_id', self::TABLE, 'tag_id', 'tag', 'id');
        }

        $this->addForeignKey('fk_cwh_tag_interest_mm_auth_item', self::TABLE, 'auth_item', 'auth_item', 'name');

        return true;
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE);
        return true;
    }
}
