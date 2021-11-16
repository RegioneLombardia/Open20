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
 * Class DeactivateAccountRule
 * @package open20\amos\admin\rules
 */
class DeactivateAccountRule extends BasicContentRule
{
    public $name = 'deactivateAccount';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        if (!\Yii::$app->user->can('ADMIN') && !\Yii::$app->user->can('AMMINISTRATORE_UTENTI')) {
            return true;
        }
        if (is_null($model) || (!($model instanceof UserProfile))) {
            return false;
        }
        return ($user != $model->user_id);
    }
}
