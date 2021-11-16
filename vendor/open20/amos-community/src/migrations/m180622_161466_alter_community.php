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
class m180622_161466_alter_community extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(Community::tableName(), 'hide_participants', $this->integer(1)->defaultValue(0)->after('community_type_id'));
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(Community::tableName(), 'hide_participants');
    }
}
