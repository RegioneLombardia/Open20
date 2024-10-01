<?php

use open20\amos\comments\AmosComments;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentBlacklist;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use kartik\grid\GridView;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var string $currentView
 */
?>

<?= DataProviderView::widget([
    'dataProvider' => $dataProvider,
    'currentView' => $currentView,
    'gridView' => [
        'columns' => [
            'comment_text' => [
                'attribute' => 'comment_text',
                'label' => AmosComments::t('amoscomments', 'Commento'),
                'format' => 'html',
                'value' => function ($model) {
                    /** @var Comment $model */
                    $commentText = $model->comment_text;
                    if (strlen($commentText) > 50) {
                        $commentText = substr($commentText, 0, 47) . '...';
                    }
                    return $commentText;
                }
            ],
//                'created_at:datetime',
//                'created_by' => [
//                    'attribute' => 'created_by',
//                    'value' => function ($model) {
//                        /** @var Comment $model */
//                        return $model->createdUserProfile->nomeCognome;
//                    }
//                ],
            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detailUrl' => \yii\helpers\Url::to(['/comments/comment-dashboard/to-validate-comment-replies']),
            ],
        ],
    ],
]); ?>