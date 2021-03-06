<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\community\rules
 * @category   CategoryName
 */

namespace lispa\amos\community\rules;

use lispa\amos\community\models\Community;
use lispa\amos\community\utilities\CommunityUtil;
use lispa\amos\core\record\Record;
use lispa\amos\core\rules\DefaultOwnContentRule;

/**
 * Class DeleteOwnCommunitiesRule
 * @package lispa\amos\community\rules
 */
class DeleteOwnCommunitiesRule extends DefaultOwnContentRule
{
    public $name = 'deleteOwnCommunities';
    
    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            /** @var Record $model */
            $model = $params['model'];
            if (!$model->id) {
                $post = \Yii::$app->getRequest()->post();
                $get = \Yii::$app->getRequest()->get();
                if (isset($get['id'])) {
                    $model = $this->instanceModel($model, $get['id']);
                } elseif (isset($post['id'])) {
                    $model = $this->instanceModel($model, $post['id']);
                }
            }
            if (!CommunityUtil::canDeleteCommunity($model)) {
                return false;
            }
            if (!empty($model->getWorkflowStatus())) {
                if (($model->getWorkflowStatus()->getId() == Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE) && !(\Yii::$app->user->can('COMMUNITY_VALIDATOR', ['model' => $model]))) {
                    return false;
                }
            }
            return ($model->created_by == $user);
        } else {
            return false;
        }
    }
}
