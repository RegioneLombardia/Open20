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
class UpdateProfileFacilitatorRule extends BasicContentRule
{
    public $name = 'updateProfileFacilitator';
    
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        // Return false if the model is null
        if (is_null($model)) {
            return false;
        }
        /** @var UserProfile $model */
        
        // Check if the profile has a facilitator
        if (is_null($model->facilitatore)) {
            return false;
        }
        
        // Check if the profile facilitator is the logged user
        return ($model->facilitatore->user_id == $user);
    }
}
