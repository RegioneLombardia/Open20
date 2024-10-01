<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\rules
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\rules;

use open20\amos\core\rules\BasicContentRule;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;

/**
 * Class OwnPartnershipProfileRule
 * @package open20\amos\partnershipprofiles\rules
 */
abstract class OwnPartnershipProfileRule extends BasicContentRule
{
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var PartnershipProfiles $model */

        $allowedStates = [
            PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT,
        ];

        if (!in_array($model->status, $allowedStates) || ($model->created_by != $user)) {
            return false;
        }

        return $this->partnershipProfileLogic($user, $item, $params, $model);
    }

    /**
     * @param string|int $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param \yii\rbac\Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[CheckAccessInterface::checkAccess()]].
     * @param PartnershipProfiles $model
     * @return bool
     */
    abstract public function partnershipProfileLogic($user, $item, $params, $model);
}
