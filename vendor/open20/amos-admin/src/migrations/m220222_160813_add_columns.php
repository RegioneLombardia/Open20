<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * 
 */
class m220222_160813_add_columns extends Migration
{
    const TABLE = "{{%user_profile}}";

    /**
     * @inheritdoc
     */
    public function up()
    {
        $schema = $this->db->schema->getTableSchema(self::TABLE, true);
        if (!isset($schema->columns['notify_tagging_user_in_content'])) {
            $this->addColumn(self::TABLE, 'notify_tagging_user_in_content',
                $this->integer(1)->defaultValue(1)->after('notify_from_editorial_staff'));
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $schema = $this->db->schema->getTableSchema(self::TABLE, true);
        if (isset($schema->columns['notify_tagging_user_in_content'])) {
            $this->dropColumn(self::TABLE, 'notify_tagging_user_in_content');
        }
    }
}