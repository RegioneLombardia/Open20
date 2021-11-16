<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfileArea;
use yii\db\Migration;

/**
 * Class m181012_162615_add_user_profile_area_field_1
 */
class m190215_132615_popolate_enable_facilitator_box extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $userIds = \Yii::$app->authManager->getUserIdsByRole('FACILITATOR');
        foreach ($userIds as $user_id){
            $userProfile = \open20\amos\admin\models\UserProfile::findOne(['user_id' => $user_id]);
            $userProfile->detachBehaviors();
            $userProfile->enable_facilitator_box = true;
            $userProfile->save(false);
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

        return true;
    }
}
