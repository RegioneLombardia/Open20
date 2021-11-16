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
class m171219_111336_add_community_field_hits extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(Community::tableName(), 'hits', $this->integer()->notNull()->defaultValue(0)->after('description'));
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(Community::tableName(), 'hits');
    }
}
