<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\rules
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\rules;

use open20\amos\core\rules\BasicContentRule;
use open20\amos\notificationmanager\models\Newsletter;
use raoul2000\workflow\base\SimpleWorkflowBehavior;

/**
 * Class OwnNewsletterRule
 * @package open20\amos\notificationmanager\rules
 */
abstract class OwnNewsletterRule extends BasicContentRule
{
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var Newsletter|SimpleWorkflowBehavior $model */
        if (!$model->id) {
            return false;
        }
        $workflowStatus = $model->getWorkflowStatus();
        return (!empty($workflowStatus) && ($workflowStatus->getId() == Newsletter::WORKFLOW_STATUS_DRAFT) && ($model->created_by == $user));
    }
}
