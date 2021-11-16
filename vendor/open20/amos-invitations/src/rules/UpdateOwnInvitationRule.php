<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations
 * @category   CategoryName
 */

namespace open20\amos\invitations\rules;

use open20\amos\invitations\models\Invitation;
use yii\rbac\Rule;

class UpdateOwnInvitationRule extends Rule
{
    public $name = 'updateOwnInvitation';

    /**
     * @inheritdoc
     */
    public function execute($loggedUserId, $item, $params)
    {
        // If the key "model" non exist return false
        if (!isset($params['model'])) {
            return false;
        }

        /** @var Invitation $model */
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
        // update only if user has created invitation and if is not sended!
        return (($model->created_by == $loggedUserId)/** && (!$model->send)*/);
    }

    /**
     * @param Invitation $model
     * @param int $modelId
     * @return mixed
     */
    protected function instanceModel($model, $modelId)
    {
        $modelClass = $model->className();
        /** @var \open20\amos\invitations\models\Invitation $modelClass */
        $instancedModel = $modelClass::findOne($modelId);
        if (!is_null($instancedModel)) {
            $model = $instancedModel;
        }
        return $model;
    }
}