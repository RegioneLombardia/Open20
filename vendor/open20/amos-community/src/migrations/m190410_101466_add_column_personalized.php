<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */
use open20\amos\community\models\Community;
use yii\db\Migration;

/**
 * Class m171219_111336_add_community_field_hits
 */
class m190410_101466_add_column_personalized extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $table = $this->db->schema->getTableSchema(\open20\amos\community\models\CommunityAmosWidgetsMm::tableName());
        if (!isset($table->columns['personalized'])) {
            $this->addColumn(\open20\amos\community\models\CommunityAmosWidgetsMm::tableName(), 'personalized', $this->integer(1)->defaultValue(0)->after('amos_widgets_id'));
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn(\open20\amos\community\models\CommunityAmosWidgetsMm::tableName(), 'personalized');
    }

}
