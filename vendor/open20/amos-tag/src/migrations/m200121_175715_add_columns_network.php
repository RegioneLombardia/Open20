<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m181012_162615_add_user_profile_area_field_1
 */
class m200121_175715_add_columns_network extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('tag', 'network_record_id', $this->integer()->defaultValue(null)->after('descrizione'));
        $this->addColumn('tag', 'cwh_config_id', $this->integer()->defaultValue(null)->after('descrizione'));
        $this->addColumn('tag', 'is_network', $this->integer(1)->defaultValue(0)->after('descrizione'));
        $this->addColumn('entitys_tags_mm', 'models_classname_id', $this->integer()->defaultValue(null)->after('entitys_tags_mm_id'));
        $this->addForeignKey('fk_entitys_tags_mm_classname_id1', 'entitys_tags_mm', 'models_classname_id', 'models_classname', 'id');

        $this->execute(
            "UPDATE entitys_tags_mm
                 inner join models_classname on (entitys_tags_mm.classname = models_classname.classname)
                 set entitys_tags_mm.models_classname_id = models_classname.id"
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropColumn('tag', 'cwh_config_id');
        $this->dropColumn('tag', 'is_network');
        $this->dropColumn('tag', 'network_record_id');
        $this->dropForeignKey('fk_entitys_tags_mm_classname_id1', 'entitys_tags_mm');
        $this->dropColumn('entitys_tags_mm', 'models_classname_id');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');

    }
}