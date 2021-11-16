<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\widgets
 * @category   CategoryName
 */

namespace open20\amos\myactivities\widgets;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\exceptions\AmosException;
use open20\amos\core\interfaces\WorkflowModelInterface;
use open20\amos\core\record\Record;
use open20\amos\myactivities\AmosMyActivities;
use open20\amos\myactivities\basic\MyActivitiesModelsInterface;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use yii\base\Widget;

/**
 * Class UserRequestConnection
 * @package open20\amos\myactivities\widgets
 */
class UserRequestConnection extends Widget
{
    /**
     * @var Record|MyActivitiesModelsInterface $model
     */
    public $model;

    /**
     * @var string $labelKey - label for activity title translation
     */
    public $labelKey;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!($this->model instanceof MyActivitiesModelsInterface)) {
            throw new AmosException(AmosMyActivities::t('amosmyactivities', 'UserRequestValidation: object not instance of MyActivitiesModelsInterface'));
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var WorkflowLogFunctionsBehavior|WorkflowModelInterface $model */
        $model = $this->model;
            $userId = $model->user_id;

            $userProfile = UserProfile::find()->andWhere(['user_id' => $userId])->one();
            if (!is_null($userProfile)) {
                return $this->render('user_request_connection', [
                    'userProfile' => $userProfile,
                    'model' => $this->model,
                    'requestTime' => $model->created_at,
                    'project' => $model->showcaseProject->title,
                    'labelKey' => $this->labelKey,

                ]);
            }
        else
        return '';
    }
}
