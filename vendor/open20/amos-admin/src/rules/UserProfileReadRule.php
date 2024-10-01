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
use Yii;
use yii\rbac\Rule;

/**
 * Class ValidatedBasicUserRule
 * @package open20\amos\admin\rules
 */
class UserProfileReadRule extends BasicContentRule
{
    /**
     * @inheritdoc
     */
    public $name = 'userProfileRead';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        if($model->attivo == true){
            return true;
        }

        return false;
    }
}
