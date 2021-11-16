<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\rules
 * @category   CategoryName
 */

namespace open20\amos\comments\rules;

use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentReply;
use open20\amos\community\models\Community;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\record\Record;
use open20\amos\core\rules\DefaultOwnContentRule;
use open20\amos\cwh\query\CwhActiveQuery;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class CommunityUpdateContentRule
 * @package open20\amos\comments\rules
 */
class CommunityUpdateContentRule extends DefaultOwnContentRule
{
    /**
     * @inheritdoc
     */
    public $name = 'communityUpdateContent';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            /** @var Record $model */
            $model          = $params['model'];
            $modelClassName = $model->className();
            if (!strcmp($modelClassName,
                    Comment::className()) || !strcmp($modelClassName,
                    CommentReply::className())) {
                $cwhModule = Yii::$app->getModule('cwh');
                $data = ArrayHelper::merge(
                            \Yii::$app->getRequest()->post(),
                            \Yii::$app->getRequest()->get()
                    );

                if (isset($data['id'])) {
                    $model = $this->instanceModel($model, $data['id']);
                }
                if ($model->id) {
                    if ($model instanceof CommentReply) {

                        /** @var Comment $comment */
                        $comment               = $model->comment;
                        /** @var Record $contextModelClassName */
                        $contextModelClassName = $comment->context;
                        /** @var Record $contextModel */
                        $model                 = $contextModelClassName::findOne($comment->context_id);

                    } elseif ($model instanceof Comment) {

                        /** @var Comment $model */
                        /** @var Record $contextModelClassName */
                        $contextModelClassName = $model->context;
                        /** @var Record $contextModel */
                        $model                 = $contextModelClassName::findOne($model->context_id);

                    }
                    return $this->validatorContentUpdatePermission($model);
                }
            }
        }

        return false;
    }

    /**
     * @param Record $model
     * @return bool
     */
    private function validatorContentUpdatePermission($model)
    {
        $cwhModule  = \Yii::$app->getModule('cwh');
        $cwhEnabled = (isset($cwhModule) && in_array(get_class($model),
                $cwhModule->modelsEnabled) && $cwhModule->behaviors);

        if ($cwhEnabled) {
            $scope = $cwhModule->getCwhScope();
            if (isset($cwhModule) && !empty($scope)) {
                $scope = $cwhModule->getCwhScope();

                $communityModule = \Yii::$app->getModule('community');
                if (isset($scope['community']) && $communityModule) {

                    $community = Community::findOne($scope['community']);

                    if (isset($communityModule->forceWorkflowSingleCommunity) && $communityModule->forceWorkflowSingleCommunity) {
                        if (CommunityUtil::hasRole($community)
                            || !$community->force_workflow) {
                            return true;
                        }
                    } else {
                        if (CommunityUtil::hasRole($community)) {
                            return true;
                        }
                    }
                }
            }
            if (empty($scope) && \Yii::$app->user->can($model->getFacilitatorRole())) {
                return true;
            }

            $validatorRole = $model->getValidatorRole();
            if (\Yii::$app->user->can('VALIDATOR') || \Yii::$app->user->can($validatorRole)) {
                return true;
            }
        $cwhActiveQuery     = new CwhActiveQuery(
            $model->className(),
            [
            'queryBase' => $model::find()->distinct()
        ]);
        $queryToValidateIds = $cwhActiveQuery->getQueryCwhToValidate(false)->select($model::tableName().'.id')->column();
        } else {
			$queryToValidateIds = $model::find()->distinct()->select($model::tableName().'.id')->column();
		}

        return (in_array($model->id, $queryToValidateIds));
    }
}