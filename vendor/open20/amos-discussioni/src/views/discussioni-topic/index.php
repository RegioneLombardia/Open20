<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\views\discussioni-topic
 * @category   CategoryName
 */
use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\discussioni\widgets\DiscussionsCarouselWidget;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\discussioni\models\search\DiscussioniTopicSearch $model
 * @var string $currentView
 */
//$this->title = AmosDiscussioni::t('amosdiscussioni', 'Discussioni');

$actionColumnDefault = '{partecipa} {update} {delete}';
$actionColumnToValidate = '{validate}{reject}';
$actionColumn = $actionColumnDefault;
if (Yii::$app->controller->action->id == 'to-validate-discussions') {
    $actionColumn = $actionColumnToValidate . $actionColumnDefault;
}

$module = \Yii::$app->getModule('discussioni');
?>

<div class="discussions-topic-index">
<?php

echo $this->render('_search', [
    'model' => $model,
    'originAction' => Yii::$app->controller->action->id
]);

echo $this->render('_order', [
    'model' => $model,
]);

echo DiscussionsCarouselWidget::widget();

$columns = array();
$columns = [
    'immagine' => [
        'label' => AmosDiscussioni::t('amosdiscussioni', 'Immagine'),
        'format' => 'html',
        'value' => function ($model) {
            /** @var DiscussioniTopic $model */
            $url = '/img/img_default.jpg';
            if (!is_null($model->discussionsTopicImage)) {
                $url = $model->discussionsTopicImage->getUrl('square_small', false, true);
            }
            $contentImage = Html::img(
                    $url,
                    ['class' => 'gridview-image', 'alt' => AmosDiscussioni::t('amosdiscussioni', 'Immagine della discussione')]
            );

            return Html::a($contentImage, $model->getFullViewUrl());
        }
    ],
    'titolo',
    [
        'attribute' => 'created_by',
        'label' => AmosDiscussioni::t('amosdiscussioni', 'Pubblicato Da'),
        'value' => function ($model) {
            $pubblicatoDa = UserProfile::findOne(['user_id' => $model->created_by]);
            $nomeCognome = $pubblicatoDa->getNomeCognome();

            return Html::a(
                    $nomeCognome,
                    ['/admin/user-profile/view', 'id' => $pubblicatoDa->id],
                    [
                        'title' => AmosDiscussioni::t(
                            'amosnews', 'Apri il profilo di {nome_profilo}',
                            ['nome_profilo' => $nomeCognome]
                        )
                    ]
            );
        },
        'format' => 'html'
    ],
    [
        'attribute' => 'created_at',
        'label' => AmosDiscussioni::t('amosdiscussioni', 'Data pubblicazione'),
        'format' => 'datetime',
    ],
    'status' => [
        'attribute' => 'status',
        'value' => function ($model) {
            /** @var DiscussioniTopic $model */
            return $model->hasWorkflowStatus() ? $model->getWorkflowStatus()->getLabel() : '--';
        }
    ],
    [
        'label' => AmosDiscussioni::t('amosdiscussioni', 'Contributi'),
        'value' => function ($model) {
            /** @var DiscussioniTopic $model */
            return $model->getDiscussionComments()->count();
        }
    ],
    'hints',
    [
        'label' => AmosDiscussioni::t('amosdiscussioni', 'Partecipanti'),
        'value' => function ($model) {
            /** @var DiscussioniTopic $model */
            $risposte = $model->getDiscussionComments();
            return $risposte->groupBy('created_by')->count();
        }
    ],
    [
        'label' => AmosDiscussioni::t('amosdiscussioni', 'Ultimo Commento/Risposta'),
        'format' => 'html',
        'value' => function ($model) {
            /** @var DiscussioniTopic $model */
            if ($model->lastCommentUser) {
                $ultima_risposta = $model->lastCommentUser->nome . ' ' . $model->lastCommentUser->cognome;
                $data = '<br />' . Yii::$app->formatter->asDatetime($model->lastCommentDate);

                return $ultima_risposta . $data;
            }

            return AmosDiscussioni::t('amosdiscussioni', 'Non ci sono ancora contributi');
        }
    ],
];
        
if ((isset($module->disableComments) && $module->disableComments)) {
    $columns['close_comment_thread'] = [
        'label' => AmosDiscussioni::t('amosdiscussioni', '#discussion_closed_list'),
        'attribute' => 'close_comment_thread',
        'value' => function ($model) {
            /** @var DiscussioniTopic $model */
            return $model->getCloseCommentThread() ? AmosDiscussioni::t('amosdiscussioni', '#force_ok') : AmosDiscussioni::t('amosdiscussioni', '#force_ko');
        }
    ];
}

$columns [] = [
    'class' => 'open20\amos\core\views\grid\ActionColumn',
    'template' => $actionColumn,
    'buttons' => [
        'validate' => function ($url, $model) {
            /** @var DiscussioniTopic $model */
            
            if (Yii::$app->getUser()->can('DiscussionValidate', ['model' => $model])) {
                return ModalUtility::addConfirmRejectWithModal([
                    'modalId' => 'validate-discussion-topic-modal-' . $model->id,
                    'modalDescriptionText' => AmosDiscussioni::t('amosdiscussioni', '#VALIDATE_DISCUSSION_MODAL_TEXT'),
                    'btnText' => AmosIcons::show('check-circle', ['class' => '']),
                    'btnLink' => Yii::$app->urlManager->createUrl([
                        '/discussioni/discussioni-topic/validate-discussion', 
                        'id' => $model->id
                    ]),
                    'btnOptions' => ['title' => AmosDiscussioni::t('amosdiscussioni', 'Publish'), 'class' => 'btn btn-tools-secondary']
                ]);
            }
        },
        'reject' => function ($url, $model) {
            /** @var DiscussioniTopic $model */
            if (Yii::$app->getUser()->can('DiscussionValidate', ['model' => $model])) {
                return ModalUtility::addConfirmRejectWithModal([
                    'modalId' => 'reject-discussion-topic-modal-' . $model->id,
                    'modalDescriptionText' => AmosDiscussioni::t('amosdiscussioni', '#REJECT_DISCUSSION_MODAL_TEXT'),
                    'btnText' => AmosIcons::show('minus-circle', ['class' => '']),
                    'btnLink' => Yii::$app->urlManager->createUrl([
                        '/discussioni/discussioni-topic/reject-discussion', 
                        'id' => $model->id
                    ]),
                    'btnOptions' => ['title' => AmosDiscussioni::t('amosdiscussioni', 'Reject'), 'class' => 'btn btn-tools-secondary']
                ]);
            }
        },
        'partecipa' => function ($url, $model, $key) {
            /** @var DiscussioniTopic $model */
            return Html::a(
                AmosIcons::show('comment'),
                [$url],
                ['class' => 'btn btn-tool-secondary', 'title' => AmosDiscussioni::t('amosdiscussioni', 'Partecipa')]
            );
        },
        'update' => function ($url, $model) {
            if (Yii::$app->user->can('DISCUSSIONITOPIC_UPDATE', ['model' => $model])) {
                $action = '/discussioni/discussioni-topic/update?id=' . $model->id;
                $options = \open20\amos\core\utilities\ModalUtility::getBackToEditPopup(
                    $model,
                    'DiscussionValidate',
                    $action,
                    ['class' => 'btn btn-tools-secondary', 'title' => Yii::t('amoscore', 'Modifica'), 'data-pjax' => '0']
                );

                return Html::a(
                    \open20\amos\core\icons\AmosIcons::show('edit'),
                    $action,
                    $options
                );
            }
        }
    ]
];
    
echo DataProviderView::widget([
    'dataProvider' => $dataProvider,
    'currentView' => $currentView,
    'gridView' => [
        'columns' => $columns,
        'enableExport' => true
    ],
    'listView' => [
        'itemView' => '_item'
    ],
    'iconView' => [
        'itemView' => '_icon'
    ],
    'mapView' => [
        'itemView' => '_map'
    ]
]);
?>
</div>
