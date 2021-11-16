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

use open20\amos\admin\models\UserProfile;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentInterface;
use open20\amos\community\models\Community;
use open20\amos\core\record\Record;
use open20\amos\mobile\bridge\modules\v1\controllers\CommentsController;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use open20\amos\mobile\bridge\modules\v1\models\User;
use yii\base\Exception;
use yii\helpers\Json;
use yii\rest\Action;

class ActionItemComments extends Action
{
    public function run()
    {
        //Request params
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();

        //Refference namespace
        $namespace = $bodyParams['namespace'];

        //Current Page
        $page = isset($bodyParams['page']) ? $bodyParams['page'] : 0;

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
        if ($record instanceof CommentInterface && \Yii::$app->user->can($canComment)) {
            //If the content is commentable
            if (!$record->isCommentable()) {
                return ['commentable' => false];
            }

            //Query all comments
            $q = Comment::find();
            $q->where([
                'context' => $namespace,
                'context_id' => $record->id,
            ]);
            $q->orderBy('created_at DESC');
            $q->limit(10);
            $q->offset($page * 10);

            //List of comments uset to fill array
            $comments = $q->all();

            //Result array to return
            $commentsArray = [];

            foreach ($comments as $comment) {
                //List of replies for this comment
                $repliesArray = [];

                $replies = $comment->commentReplies;

                foreach ($replies as $reply) {
                    //Creator profile
                    $rep_owner = UserProfile::findOne(['id' => $reply->created_by]);

                    $repliesArray[] = [
                        'id' => $reply->id,
                        'comment_text' => strip_tags($reply->comment_reply_text),
                        'created_at' => $reply->created_at,
                        'owner' => [
                            'nome' => $owner->nome,
                            'cognome' => $owner->cognome,
                            'presentazione_breve' => $rep_owner->presentazione_breve,
                            'avatarUrl' => $owner->avatarWebUrl,
                        ],
                    ];
                }

                //Creator profile
                $owner = UserProfile::findOne(['id' => $comment->created_by]);

                $commentsArray[] = [
                    'id' => $comment->id,
                    'comment_text' => html_entity_decode(strip_tags($comment->comment_text)),
                    'created_at' => $comment->created_at,
                    'replies' => $repliesArray,
                    'owner' => [
                        'nome' => $owner->nome,
                        'cognome' => $owner->cognome,
                        'presentazione_breve' => $owner->presentazione_breve,
                        'avatarUrl' => $owner->avatarWebUrl,
                    ],
                ];
            }


            return [
                'commentable' => true,
                'comments' => $commentsArray
            ];
        }

        return ['commentable' => false];
    }
}