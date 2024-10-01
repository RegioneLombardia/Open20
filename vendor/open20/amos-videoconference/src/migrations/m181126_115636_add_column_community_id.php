<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\videoconference\models\Videoconf;
use yii\db\Migration;

/**
 * Class m171219_111336_add_community_field_hits
 */
class m181126_115636_add_column_community_id extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(Videoconf::tableName(), 'community_id', $this->integer()->defaultValue(null)->comment('Community')->after('reminder_sent'));
        $this->addForeignKey('fk_videoconf_community_id1', Videoconf::tableName(), 'community_id', 'community', 'id');

    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(Videoconf::tableName(), 'community_id');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');


    }
}
