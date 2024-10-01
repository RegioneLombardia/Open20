<?php

use yii\db\Migration;
use yii\db\sqlite\Schema;

class m230329_164623_create_expires_at_in_socialwall_tokens_table extends Migration{

    const TABLE_NAME = '{{%socialwall_tokens}}';

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $table = Yii::$app->db->schema->getTableSchema('socialwall_tokens');
        if (!isset($table->columns['expires_at'])) {
            $this->addColumn(self::TABLE_NAME, 'expires_at', $this->dateTime()->null()->after('data')->comment('Will expire on'));
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropColumn(self::TABLE_NAME, 'expires_at');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }

}
