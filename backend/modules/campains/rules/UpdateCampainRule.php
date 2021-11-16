<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\campains\rules
 * @category   CategoryName
 */

namespace backend\modules\campains\rules;

use backend\modules\campains\models\PreferenceCampain;
use open20\amos\core\rules\BasicContentRule;

/**
 * Class UpdateCampainRule
 * @package backend\modules\campains\rules
 */
class UpdateCampainRule extends BasicContentRule
{
    public $name = 'updateCampain';
    
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        $campain = $params['model'];
        if (!empty($campain)) {

            if ($campain->status == PreferenceCampain::PREFERENCE_CAMPAIN_WORKFLOW_STATUS_TOBEAPPROVED){
                return true;
            }

        }

        return false;  
    }
}
