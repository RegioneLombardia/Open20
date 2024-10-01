<?php

use yii\db\Migration;
use yii\db\sqlite\Schema;

class m230329_233123_edit_data_default_value_in_socialwall_tokens_table extends Migration{

    const TABLE_NAME = '{{%socialwall_tokens}}';

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $table = Yii::$app->db->schema->getTableSchema('socialwall_tokens');
        if (!isset($table->columns['data'])) {
            $this->alterColumn(self::TABLE_NAME, 'data', $this->json()->after('type')->null()->defaultValue('{}')->comment('Token Data'));
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->alterColumn(self::TABLE_NAME, 'data', $this->json()->after('type')->null()->comment('Token Data'));

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }

}
