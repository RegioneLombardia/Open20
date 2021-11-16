<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Add field 'context' to table community
 * context contains the creator model classname (eg. Community, Project management classname, events classname,..)
 * Class m170323_095454_community_table_add_field_context
 */
class m170323_095454_community_table_add_field_context extends Migration
{
    const COMMUNITY = 'community';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::COMMUNITY, 'context', 'varchar(255)');
        $this->update(self::COMMUNITY, ['context' => \open20\amos\community\models\Community::className()]);
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::COMMUNITY, 'context');
        return true;
    }
}
