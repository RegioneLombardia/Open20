<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    fabbisogni-online-platform\console\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m210712_160354_add_user_profile_field_deactivated_at
 */
class m210712_160354_add_user_profile_field_deactivated_at extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(UserProfile::tableName(), 'deactivated_at', $this->dateTime()->defaultValue(null)->after('attivo'));
        $this->update(UserProfile::tableName(), ['deactivated_at' => new Expression('NOW()')], ['attivo' => 0]);
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(UserProfile::tableName(), 'deactivated_at');
        return true;
    }
}
