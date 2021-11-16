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
 * Class m170705_085102_alter_user_profile_add_googleplus
 */
class m170705_085102_alter_user_profile_add_googleplus extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(UserProfile::tableName(), 'googleplus', $this->string(255)->null()->defaultValue(null)->comment('Profilo Google Plus')->after('linkedin'));
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(UserProfile::tableName(), 'googleplus');
    }
}
