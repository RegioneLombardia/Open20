<?php

use yii\db\Migration;
use yii\db\sqlite\Schema;

class m230419_135723_add_socialwall_posts_profiles_id_field_to_socialwall_posts_table extends Migration {

    const TABLE_NAME = '{{%socialwall_posts}}';

    /**
     * @inheritdoc
     */
    public function safeUp() {

        if ($this->db->schema->getTableSchema(self::TABLE_NAME, true) !== null) {
            $table = Yii::$app->db->schema->getTableSchema('socialwall_posts');
            if (!isset($table->columns['socialwall_posts_profiles_id'])) {
                $this->addColumn(self::TABLE_NAME, 'socialwall_posts_profiles_id', $this->integer()->null()->after('publication_date')->comment('Profile ID'));
                $this->addForeignKey('socialwall_posts_socialwall_posts_profiles_id_fk', self::TABLE_NAME, 'socialwall_posts_profiles_id', 'socialwall_posts_profiles', "id");
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

        $this->dropForeignKey('socialwall_posts_socialwall_posts_profiles_id_fk', self::TABLE_NAME);
        $this->dropColumn(self::TABLE_NAME, 'socialwall_posts_profiles_id');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }

}
