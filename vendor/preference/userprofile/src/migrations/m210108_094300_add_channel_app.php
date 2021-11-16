<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use yii\db\Migration;

/**
 * Class m210108_094300_add_channel_app
 */
class m210108_094300_add_channel_app extends Migration
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('preference_channel', ['id' => 4, 'title' => 'App', 'active' => 1]);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('preference_channel', ['id' => 4]);
        return true;
    }
}
