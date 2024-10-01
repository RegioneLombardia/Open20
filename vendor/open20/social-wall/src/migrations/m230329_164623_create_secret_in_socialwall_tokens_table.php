<?php

use yii\db\Migration;
use yii\db\sqlite\Schema;

class m230329_164623_create_secret_in_socialwall_tokens_table extends Migration{

    const TABLE_NAME = '{{%socialwall_tokens}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('socialwall_tokens');
        if (!isset($table->columns['secret'])) {
            $this->addColumn(self::TABLE_NAME, 'secret', $this->string(30)->null()->after('data')->comment('Secret for getting api'));
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropColumn(self::TABLE_NAME, 'secret');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }

}
