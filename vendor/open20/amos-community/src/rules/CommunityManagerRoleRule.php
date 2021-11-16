<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\rules
 * @category   CategoryName
 */

namespace open20\amos\community\rules;

use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\rules\DefaultOwnContentRule;
use yii\helpers\Url;

/**
 * Class CreateSubcommunitiesRule
 * @package open20\amos\community\rules
 */
class CommunityManagerRoleRule extends DefaultOwnContentRule
{
    /**
     * @inheritdoc
     */
    public $name = 'communityManagerRole';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $cwhModule = \Yii::$app->getModule('cwh');
        if (isset($params['model'])) {
            if (isset($cwhModule)) {
                $cwhModule->setCwhScopeFromSession();
                if (!empty($cwhModule->userEntityRelationTable)) {
                    $entityId = $cwhModule->userEntityRelationTable['entity_id'];
                    $model = Community::findOne($entityId);
                    return $this->hasRole($user, $model);
                }
            }
        }
        else {
            //  Check you have the permission when you are in the community dashboard
           $currentUrl = explode("?", Url::current());
            if($currentUrl[0]=='/community/join/index' || $currentUrl[0]=='/community/join'){
                $post = \Yii::$app->getRequest()->post();
                $get = \Yii::$app->getRequest()->get();
                if (isset($get['id'])) {
                    $model = Community::findOne($get['id']);
                } elseif (isset($post['id'])) {
                    $model = Community::findOne($post['id']);
                }
                if(!empty($model)) {
                    if (!empty($model->getWorkflowStatus())) {
                        if (($model->getWorkflowStatus()->getId() == Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE && $model->validated_once !=1 ) && !(\Yii::$app->user->can('COMMUNITY_VALIDATOR', ['model' => $model]))) {
                            return false;
                        }
                    }
                    return $this->hasRole($user, $model);
                }
            }
        }
        return false;
    }

    /**
     * @param $user_id
     * @param $model Community
     * @return bool
     */
    public function hasRole($user_id, $model){
        $communityUserMm = CommunityUserMm::find()
            ->andWhere(['user_id' => $user_id])
            ->andWhere(['community_id' => $model->id])
            ->andWhere(['role' => CommunityUserMm::ROLE_COMMUNITY_MANAGER])
            ->one();

        if(!empty($communityUserMm)){
            return true;
        }
        return false;
    }
}
