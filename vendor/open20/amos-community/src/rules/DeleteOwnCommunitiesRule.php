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
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\record\Record;
use open20\amos\core\rules\DefaultOwnContentRule;

/**
 * Class DeleteOwnCommunitiesRule
 * @package open20\amos\community\rules
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
            
            /**
             * Riga di return sostituita da return true per dare il permesso di cancellare una community a qualsiasi community manager della community
             * e non solo al suo creatore. Il check se l'utente loggato è community manager per questa community è nel canDeleteCommunity.
             */
//            return ($model->created_by == $user);
            
            return true;
        } else {
            return false;
        }
    }
}
