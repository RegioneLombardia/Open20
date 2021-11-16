<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\rules;

use open20\amos\community\models\Community;
use open20\amos\core\rules\DefaultOwnContentRule;

/**
 * Class UpdateCommunitiesManagerRule
 * @package open20\amos\community\rules
 */
class UpdateCommunitiesManagerRule extends DefaultOwnContentRule
{
    public $name = 'updateCommunitiesManager';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            /** @var Community $model */
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
            if (!$model instanceof Community) {
                return false;
            }


            if (!empty($model->getWorkflowStatus())) {
                if (($model->getWorkflowStatus()->getId() == Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE ) && !(\Yii::$app->user->can('COMMUNITY_VALIDATOR', ['model' => $model]))) {
                    return false;
                }
            }
            return ($model->isCommunityManager($user));
        } else {
            return false;
        }
    }
}
