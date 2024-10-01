<?php

use yii\db\Migration;

class m210720_103715_add_indexes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->createIndex('idx_2', 'entitys_tags_mm', ['models_classname_id', 'record_id', 'root_id', 'deleted_at']);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropIndex('idx_2', 'entitys_tags_mm');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }
}
