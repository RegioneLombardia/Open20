<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiCategoryCommunityMm;
use yii\db\Migration;

/**
 * Class m171206_092631_add_documenti_fields_1
 */
class m190325_160731_add_field_category_comunity_mm extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $table = $this->db->schema->getTableSchema(DocumentiCategoryCommunityMm::tableName());
        
        if (!(isset($table->columns['visible_to_cm']))) {
            $this->addColumn(DocumentiCategoryCommunityMm::tableName(), 'visible_to_cm', $this->integer(1)->null()->defaultValue(null)->after('community_id'));
            $this->addColumn(DocumentiCategoryCommunityMm::tableName(), 'visible_to_participant', $this->integer(1)->null()->defaultValue(1)->after('community_id'));
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn(\open20\amos\documenti\models\DocumentiCategoryCommunityMm::tableName(), 'visible_to_cm');
        $this->dropColumn(\open20\amos\documenti\models\DocumentiCategoryCommunityMm::tableName(), 'visible_to_participant');
    }

}
