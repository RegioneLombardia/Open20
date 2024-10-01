<?php

use open20\amos\community\models\Community;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use open20\amos\news\AmosNews;
use open20\amos\news\models\News;
use yii\helpers\Html;


$newsModule = AmosNews::instance();
$hidePubblicationDate = $newsModule->hidePubblicationDate;
$hideDataRimozioneView = $newsModule->hideDataRimozioneView;

?>

<?= DataProviderView::widget([
    'dataProvider' => $dataProvider,
    'currentView' => $currentView,
    'gridView' => [
        //'filterModel' => $model,
        'columns' => [
            'status' => [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var News $model */
                    $status = $model->hasWorkflowStatus() ? AmosNews::t('amosnews', $model->getWorkflowStatus()->getLabel()) : '--';

                    if ($status == 'In richiesta di pubblicazione') {
                        $icon = 'mdi mdi-cloud-upload-outline text-muted';
                    } else if ($status == 'Bozza') {
                        $icon = 'mdi mdi-cloud-off-outline text-muted';
                    } else {
                        $icon = 'mdi mdi-cloud text-primary';
                    }
                    return Html::tag(
                        'p',
                        Html::tag(
                            'span',
                            '',
                            [
                                'class' => $icon . ' ' . 'mdi-24px',
                                'title' => $status
                            ]
                        ),
                        ['class' => 'm-t-0 m-b-0 text-center']
                    );
                }
            ],
            'immagine' => [
                'label' => AmosNews::t('amosnews', 'Immagine'),
                'format' => 'html',
                'value' => function ($model) {
                    /** @var News $model */
                    $url = '/img/img_default.jpg';
                    if (!is_null($model->newsImage)) {
                        $url = $model->newsImage->getWebUrl('table_small', false, true);
                    }
                    $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => AmosNews::t('amosnews', 'Immagine della notizia')]);
                    return $contentImage;
//                        return Html::a($contentImage, $model->getFullViewUrl());
                }
            ],
            'titolo' => [
                'attribute' => 'titolo',
                'label' => AmosNews::t('amosnews', 'Titolo'),
                'value' => function ($model) {
                    return '<strong>' . $model->title . '</strong>';
                },
                'format' => 'html'
            ],
            'created_by' => [
                'attribute' => 'created_by',
                'label' => AmosNews::t('amosnews', 'Creatore'),
                'value' => function ($model) {
                    return $model->createdUserProfile->nomeCognome;
                },
                'format' => 'html'
            ],
            'data_pubblicazione' => [
                'label' => $hidePubblicationDate ? AmosNews::t('amosnews', 'Pubblicato il') : AmosNews::t('amosnews', 'Inizio pubblicazione'),
                'attribute' => 'data_pubblicazione',
                'value' => function ($model) {
                    /** @var News $model */
                    $data = (is_null($model->data_pubblicazione)) ? AmosNews::t('amosnews', 'Immediata') : Yii::$app->formatter->asDatetime($model->data_pubblicazione, 'php:d/m/Y H:i');
                    return Html::tag('p', $data, ['class' => 'm-t-0 m-b-0 ', 'style' => 'white-space:nowrap;']);
                },
                'format' => 'raw',
                'visible' => !$newsModule->enableAgid,
            ],
//            'data_rimozione' => [
//                'label' => AmosNews::t('amosnews', 'Fine pubblicazione'),
//                'visible' => (!$hidePubblicationDate && !$hideDataRimozioneView && !$newsModule->enableAgid),
//                'attribute' => 'data_rimozione',
//                'value' => function ($model) {
//                    /** @var News $model */
//                    $data = (is_null($model->data_rimozione) || $model->data_rimozione == '9999-12-31 00:00:00') ? AmosNews::t('amosnews', 'Mai') : Yii::$app->formatter->asDatetime($model->data_rimozione, 'php:d/m/Y H:i');
//                    return Html::tag('p', $data, ['class' => 'm-t-0 m-b-0 ', 'style' => 'white-space:nowrap;']);
//                },
//                'format' => 'raw',
//            ],
            'news_categorie_id' => [
                'attribute' => 'newsCategorie.titolo',
                'label' => AmosNews::t('amosnews', 'Categoria'),
                'visible' => $newsModule->showCategory,
            ],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{view}{clone}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        /** @var News $model */
                        return Html::a('<span class="mdi mdi-eye-outline"></span>', $url, [
                            'class' => 'btn btn-tools-secondary',
                            'title' => AmosNews::t('amosnews', '[Apri in una nuova scheda] {title}', ['title' => $model->titolo]),
                            'target' => '_blank'
                        ]);
                    },
                    'clone' => function ($url, $model) {
                        /** @var News $model */
                        return Html::a('<span class="mdi mdi-content-copy"></span>',
                            ['/news/news/clone-news', 'communityId' => Yii::$app->request->getQueryParam('communityId'), 'newsId' => $model->id],
                            [
                                'class' => 'btn btn-tools-secondary',
                                'title' => AmosNews::t('amosnews', 'Importa {title}', ['title' => $model->titolo]),
                                'data-confirm' => AmosNews::t('amosnews', 'Sei sicuro di voler importare questa notizia all\'interno della community "{communityName}"?', [
                                    'communityName' => Community::findOne(Yii::$app->request->getQueryParam('communityId'))->name
                                ]),
                            ]);
                    }
                ]
            ],
        ],
    ],
]);
?>