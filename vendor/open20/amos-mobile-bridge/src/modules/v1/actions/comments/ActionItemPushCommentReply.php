<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge\modules\v1\actions\comments;

use open20\amos\comments\models\CommentInterface;
use open20\amos\comments\models\CommentReply;
use Yii;
use yii\rest\Action;

class ActionItemPushCommentReply extends Action
{
    public function run()
    {
        //Request params
        $bodyParams = Yii::$app->getRequest()->getBodyParams();

        if(empty($bodyParams['comment_text'])) {
            return false;
        }
        
        if(empty($bodyParams['comment_id'])) {
            return false;
        }

        //Refference namespace
        $namespace = $bodyParams['namespace'];

        /**
         * Class for this fetch, expected Record
         * @var $class Record
         */
        $class = new $namespace();

        //Record interested
        if (!empty($bodyParams['id'])) {
            $record = $class::findOne(['id' => $bodyParams['id']]);
        } else {
            $record = new $class();
        }

        //Comments permission
        $canComment = 'COMMENTS_CONTRIBUTOR';

        //Commentable
        if ($record instanceof CommentInterface && Yii::$app->user->can($canComment)) {
            //If the content is commentable
            if (!$record->isCommentable()) {
                return ['commentable' => false];
            }

            //Store comment
            $comment = new CommentReply();
            $comment->comment_reply_text = strip_tags($bodyParams['comment_text']);
            $comment->comment_id = $bodyParams['comment_id'];
            $comment->save(false);

            return [
                'commentable' => true
            ];
        }

        return ['commentable' => false];
    }
}