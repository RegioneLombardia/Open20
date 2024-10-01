<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti
 * @category   CategoryName
 */

namespace open20\amos\documenti\rules;

use open20\amos\core\rules\DefaultOwnContentRule;
use open20\amos\documenti\models\Documenti;

class UpdateOwnDocumentiRule extends DefaultOwnContentRule
{
    public $name = 'updateOwnDocumenti';

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

            if(isset($params['newVersion'])){
                if (!empty($model->getWorkflowStatus())) {
                    if ($model->getWorkflowStatus()->getId() == Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO &&  $model->created_by == $user) {
                        return true;
                    }
                }
            }
            if (!empty($model->getWorkflowStatus())) {
                if (($model->getWorkflowStatus()->getId() == Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA || \Yii::$app->getUser()->can('DocumentValidate', ['model' => $model])) && $model->created_by == $user) {
                    return true;
                }
            }
            //  return ($model->created_by == $user);
        } else {
            return false;
        }

        return false;
    }
}
