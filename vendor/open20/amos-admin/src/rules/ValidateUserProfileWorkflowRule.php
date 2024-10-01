<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\rules
 * @category   CategoryName
 */

namespace open20\amos\admin\rules;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\rules\BasicContentRule;

/**
 * Class UpdateProfileFacilitatorRule
 * @package open20\amos\admin\rules
 */
class ValidateUserProfileWorkflowRule extends BasicContentRule
{
    public $name = 'ValidateUserProfileWorkflow';
    
    /**
     * Facilitator cannot validate his own profile
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        $module = \Yii::$app->getModule(AmosAdmin::getModuleName());
        $currentProfile = UserProfile::find()->andWhere(['user_id' => $user])->one();
        // Return false if the model is null
        if (is_null($model)) {
            return false;
        }

        /** @var UserProfile $model */
        if(\Yii::$app->user->can('FACILITATOR')){
            if($model->user_id == $user) {
                return false;
            }
            // facilitator can validate only his own users
            if($module && $module->facilitatorCanValidateOnlyOwnUser && $currentProfile){
                if($model->facilitatore_id != $currentProfile->id){
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}
