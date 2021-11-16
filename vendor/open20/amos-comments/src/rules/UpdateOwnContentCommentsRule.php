<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\rules
 * @category   CategoryName
 */

namespace open20\amos\comments\rules;

use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentReply;
use open20\amos\core\rules\DefaultOwnContentRule;

/**
 * Class UpdateOwnContentCommentsRule
 * @package open20\amos\comments\rules
 */
class UpdateOwnContentCommentsRule extends DefaultOwnContentRule
{
    public $name = 'updateOwnContentComments';
    
    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            /** @var \open20\amos\core\record\Record $model */
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
            
            if ($model instanceof CommentReply) {
                if($model->isNewRecord){
                    return true;
                }else {
                    /** @var Comment $comment */
                    $comment = $model->comment;
                    /** @var \open20\amos\core\record\Record $contextModelClassName */
                    $contextModelClassName = $comment->context;
                    /** @var \open20\amos\core\record\Record $contextModel */
                    $contextModel = $contextModelClassName::findOne($comment->context_id);
                    return ($contextModel->created_by == $user);
                }
            } elseif ($model instanceof Comment) {
                if($model->isNewRecord){
                    return true;
                }else {
                    /** @var Comment $model */
                    /** @var \open20\amos\core\record\Record $contextModelClassName */
                    $contextModelClassName = $model->context;
                    /** @var \open20\amos\core\record\Record $contextModel */
                    $contextModel = $contextModelClassName::findOne($model->context_id);
                    return ($contextModel->created_by == $user);
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
