<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\campains\rules
 * @category   CategoryName
 */

namespace backend\modules\operators\rules;

use open20\amos\core\rules\BasicContentRule;
use Yii;
use yii\helpers\VarDumper;

/**
 * Class UpdateOwnOperatorRule
 * @package backend\modules\campains\rules
 */
class UpdateOwnOperatorRule extends BasicContentRule
{
    public $name = 'updateOwnOperator';
    
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        if(isset($model) && ($model->user_id == Yii::$app->user->id)) {
            return true;
        }
        return false;
    }
    
}
