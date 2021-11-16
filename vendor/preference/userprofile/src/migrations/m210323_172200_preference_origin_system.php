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
 * Class m210323_172200_preference_origin_system
 */
class m210323_172200_preference_origin_system extends Migration
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
        $this->insert('preference_origin_system', ['id' => 2, 'name' => 'app']);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('preference_origin_system', ['id' => 2]);
        return true;
    }
}
