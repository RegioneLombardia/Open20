<?php

use open20\amos\comments\AmosComments;
use open20\amos\comments\models\CommentBlacklist;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;

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
            ['class' => 'yii\grid\SerialColumn'],
            'word',
//                'created_at:datetime',
//                'created_by',
//                'updated_at:datetime',
//                'updated_by',
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{update}{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        /** @var CommentBlacklist $model */
                        return \yii\helpers\Html::a(
                            AmosIcons::show('edit'),
                            ['/comments/comment-blacklist/update', 'id' => $model->id],
                            [
                                'title' => \Yii::t('amoscore', 'Update'),
                                'class' => 'btn btn-tool-secondary'
                            ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        /** @var CommentBlacklist $model */
                        return \yii\helpers\Html::a(
                            AmosIcons::show('delete'),
                            ['/comments/comment-blacklist/delete', 'id' => $model->id],
                            [
                                'title' => AmosComments::t('amoscomments', 'Cancella'),
                                'class' => 'btn btn-danger-inverse',
                                'data-confirm' => AmosComments::t('amoscomments', 'Sei sicuro di voler cancellare questo termine?'),
                                'data-method' => 'post'
                            ]
                        );
                    }
                ]
            ],
        ],
    ],
]); ?>