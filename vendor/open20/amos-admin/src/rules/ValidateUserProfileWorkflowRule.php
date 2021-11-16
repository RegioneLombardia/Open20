<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\rules
 * @category   CategoryName
 */

namespace open20\amos\admin\rules;

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
        // Return false if the model is null
        if (is_null($model)) {
            return false;
        }

        /** @var UserProfile $model */
        if(\Yii::$app->user->can('FACILITATOR')){
            if($model->user_id == $user)
                return false;
            else return true;
        }
        return false;
    }
}
