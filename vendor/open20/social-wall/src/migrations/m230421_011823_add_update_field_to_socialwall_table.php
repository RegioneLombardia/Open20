<?php

use yii\db\Migration;
use yii\db\sqlite\Schema;

class m230421_011823_add_update_field_to_socialwall_table extends Migration {

    const TABLE_NAME = '{{%socialwall}}';

    /**
     * @inheritdoc
     */
    public function safeUp() {

        if ($this->db->schema->getTableSchema(self::TABLE_NAME, true) !== null) {
            $table = Yii::$app->db->schema->getTableSchema('socialwall');
            if (!isset($table->columns['update'])) {
                $this->addColumn(self::TABLE_NAME, 'update', $this->boolean()->defaultValue(true)->after('type')->comment('Allow automatic updates for type livewall'));
            }
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella non esiste";
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropColumn(self::TABLE_NAME, 'update');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }

}
