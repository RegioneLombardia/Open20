<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\core\record\Record;
use open20\amos\mobile\bridge\modules\v1\actions\comments\ActionItemComments;
use open20\amos\mobile\bridge\modules\v1\actions\comments\ActionItemPushComment;
use open20\amos\mobile\bridge\modules\v1\actions\comments\ActionItemPushCommentReply;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

class CommentsController extends DefaultController
{
    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [];
    }

    public function actions()
    {
        return [
            'item-comments' => [
                'class' => ActionItemComments::className(),
                'modelClass' => Record::className(),
            ],
            'item-push-comment' => [
                'class' => ActionItemPushComment::className(),
                'modelClass' => Record::className(),
            ],
            'item-push-comment-reply' => [
                'class' => ActionItemPushCommentReply::className(),
                'modelClass' => Record::className(),
            ],
        ];
    }

}