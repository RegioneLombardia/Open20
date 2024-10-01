<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\rules
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\rules;

use open20\amos\core\rules\BasicContentRule;
use open20\amos\organizzazioni\models\Profilo;
use Yii;

class DeleteOwnProfiloRule extends BasicContentRule
{
    public $name = 'deleteOwnProfilo';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        if (!empty($model->getWorkflowStatus())) {

            if (($model->getWorkflowStatus()->getId() == Profilo::PROFILO_WORKFLOW_STATUS_DRAFT || Yii::$app->getUser()->can($model->getValidatorRole(), ['model' => $model])) && $model->created_by == $user) {
                return true;
            }
        }
        return false;
    }
}