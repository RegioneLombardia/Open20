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
class m180110_095836_add_columns_ore extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(Videoconf::tableName(), 'reminder_sent', $this->integer(1)->defaultValue(0)->after('begin_date_hour'));
        $this->addColumn(Videoconf::tableName(), 'notification_before_conference', $this->integer()->defaultValue(null)->after('begin_date_hour'));
        $this->addColumn(Videoconf::tableName(), 'end_date_hour', $this->datetime()->defaultValue(null)->after('begin_date_hour'));


    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(Videoconf::tableName(), 'notification_before_conference');
        $this->dropColumn(Videoconf::tableName(), 'end_date_hour');
    }
}
