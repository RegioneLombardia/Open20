<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfileArea;
use yii\db\Migration;

/**
 * Class m181012_162615_add_user_profile_area_field_1
 */
class m200211_093715_add_indexes extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $this->createIndex('idx_entitys_tags_mm_classname_id_record_id', 'entitys_tags_mm', ['models_classname_id', 'record_id']);
        $this->createIndex('idx_entitys_tags_mm_record_id', 'entitys_tags_mm', 'record_id');
        $this->addForeignKey('fk_entitys_tags_mm_tag_id', 'entitys_tags_mm', 'tag_id', 'tag', 'id');

        $this->createIndex('idx_tag_network_record_id', 'tag', 'network_record_id');
        $this->createIndex('idx_tag_cwh_config_id', 'tag', 'cwh_config_id');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropForeignKey('fk_entitys_tags_mm_tag_id', 'entitys_tags_mm');
        $this->dropIndex('idx_entitys_tags_mm_classname_id_record_id', 'entitys_tags_mm');
        $this->dropIndex('idx_entitys_tags_mm_record_id', 'entitys_tags_mm');
        $this->dropIndex('idx_tag_network_record_id', 'tag');
        $this->dropIndex('idx_tag_cwh_config_id', 'tag');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }
}
