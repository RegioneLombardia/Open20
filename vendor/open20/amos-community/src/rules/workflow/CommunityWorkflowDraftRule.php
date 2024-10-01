<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\projectmanagement\rules\workflow
 * @category   CategoryName
 */

namespace open20\amos\community\rules\workflow;


use open20\amos\community\models\Community;
use open20\amos\core\rules\BasicContentRule;
use Yii;

class CommunityWorkflowDraftRule extends BasicContentRule
{

    public $name = 'communityWorkflowDraft';

    /**
     * @param int|string $user
     * @param \yii\rbac\Item $item
     * @param array $params
     * @param Community $model
     * @return bool
     * @throws \open20\amos\community\exceptions\CommunityException
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        if(\Yii::$app->user->can('COMMUNITY_VALIDATOR')){
            return true;
        }

        $modelOld = Community::findOne($model->id);
        if($modelOld->status == Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED){
            return false;
        }
        //se la manifestazione è in carico a qualcouno può modificare lo stoto solo chi l'ha incarico

        return false;
    }

}