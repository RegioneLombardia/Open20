<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\rbac;

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Class UpdateOwnUserProfile
 * @package open20\amos\community\rbac
 */
class UpdateOwnNetworkCommunity extends Rule
{
    public $name = 'isCommunityInYourNetwork';
    public $description = '';

    public function execute($user, $item, $params)
    {
        /** @var Community $model */
        $model = ((isset($params['model']) && $params['model']) ? $params['model'] : new Community());

        if (!isset($model->id)) {
            $post = \Yii::$app->getRequest()->post();
            $get = \Yii::$app->getRequest()->get();
            if (isset($get['id'])) {
                $model = $this->instanceModel($model, $get['id']);
            } elseif (isset($post['id'])) {
                $model = $this->instanceModel($model, $post['id']);
            }
        }

        if (($model instanceof Community)) {
            if (!empty($model->getWorkflowStatus())) {
                if (($model->getWorkflowStatus()->getId() == Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE && $model->validated_once !=1 ) && !(\Yii::$app->user->can('COMMUNITY_VALIDATOR', ['model' => $model]))) {
                    return false;
                }
            }
            return $model->isNetworkUser($model->id, $user);
        }
        return false;
    }

    /**
     * @param Community $model
     * @param int $modelId
     * @return mixed
     */
    private function instanceModel($model, $modelId)
    {
        /** @var UserProfile $userProfileInstance */
        $userProfileInstance = AmosCommunity::instance()->createModel('Community');
        $instancedModel = $userProfileInstance::findOne($modelId);

        if (!is_null($instancedModel)) {
            $model = $instancedModel;
        }

        return $model;
    }
}
