<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\news\rules;

use open20\amos\core\rules\DefaultOwnContentRule;
use open20\amos\news\models\News;
use Yii;

class PublishOnHomePageNewsRule extends DefaultOwnContentRule
{
    /*
     * Perms on publish on Home Page News
     */
    public $name = 'publishOnHomePageNews';

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

            if (!empty($model->getWorkflowStatus())) {
                if (
                    (
                        $model->getWorkflowStatus()->getId() == News::NEWS_WORKFLOW_STATUS_VALIDATO
                        || Yii::$app->getUser()->can('PublishOnHomePageNews', ['model' => $model])
                    )
                ) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * @param Record $model
     * @param int $modelId
     * @return mixed
     */
    protected function instanceModel($model, $modelId)
    {
        $modelClass = $model->className();
        /** @var Record $modelClass */
        $instancedModel = $modelClass::findOne($modelId);
        if (!is_null($instancedModel)) {
            $model = $instancedModel;
        }
        
        return $model;
    }
}
