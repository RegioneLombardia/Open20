<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\projectmanagement\rules
 * @category   CategoryName
 */

namespace lispa\amos\projectmanagement\rules;

use lispa\amos\projectmanagement\models\ProjectsProgressDetectionPeriod;
use lispa\amos\projectmanagement\utility\ProjectManagementUtility;
use yii\rbac\Rule;

class ProjectsProgressDetectionPeriodUpdateRule extends Rule
{

    public $name = 'projectsProgressDetectionPeriodUpdate';

    /**
     * Executes the rule.
     *
     * @param string|int $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param \yii\rbac\Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[CheckAccessInterface::checkAccess()]].
     * @return bool a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        // if this detection period have progress statement or cost linked is not possible to delete...
        /** @var ProjectsProgressDetectionPeriod $model */
        $model = isset($params['model']) ? $params['model'] : null;
        if (is_null($model)) return false;

        if ($model->isNewRecord) {
            $post = \Yii::$app->getRequest()->post();
            $get = \Yii::$app->getRequest()->get();
            if (isset($get['id'])) {
                $model = $this->instanceModel($model, $get['id']);
            } elseif (isset($post['id'])) {
                $model = $this->instanceModel($model, $post['id']);
            }
        }

        if ($model->status == ProjectsProgressDetectionPeriod::PROJECTSDETECTIONPERIOD_WORKFLOW_STATUS_CLOSED) {
            return false;
        }

        return ProjectManagementUtility::loggedUsedIsCommunityManager();
    }

    /**
     * @param ProjectsProgressDetectionPeriod $model
     * @param int $modelId
     * @return mixed
     */
    private function instanceModel($model, $modelId)
    {
        /** @var ProjectsProgressDetectionPeriod $instance */
        $instance = new ProjectsProgressDetectionPeriod;
        $instancedModel = $instance::findOne($modelId);
        if (!is_null($instancedModel)) {
            $model = $instancedModel;
        }
        return $model;
    }


}