<?php

use open20\amos\admin\models\UserProfileArea;
use yii\db\Migration;

class m210720_093715_add_indexes  extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->createIndex('idx_tag_codice', 'tag', 'codice');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropIndex('idx_tag_codice', 'tag');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }
}
