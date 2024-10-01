<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\videoconference\models\VideoconfUsersMm;
use yii\db\Migration;

/**
 * Class m230825_114100_add_column_invited_only_table_videoconf_users_mm
 */
class m230825_114100_add_column_invited_only_table_videoconf_users_mm extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(VideoconfUsersMm::tableName(), 'invited_only', $this->integer(1)->defaultValue(1)->after('user_profile_id'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(VideoconfUsersMm::tableName(), 'invited_only');
    }
}
