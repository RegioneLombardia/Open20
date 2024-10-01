<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\news\AmosNews;
use open20\amos\news\controllers\NewsController;
use open20\amos\news\models\News;
use open20\amos\news\widgets\NewsCarouselWidget;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\news\models\search\NewsSearch $searchModel
 * @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard
 * @var string $currentView
 */

$actionColumnDefault = '{view}{update}{delete}';
$actionColumnToValidate = '{validate}{reject}';
$actionColumn = $actionColumnDefault;
if (Yii::$app->controller->action->id == 'to-validate-news') {
    $actionColumn = $actionColumnToValidate . $actionColumnDefault;
}
$hidePubblicationDate = Yii::$app->controller->newsModule->hidePubblicationDate;
$hideDataRimozioneView = Yii::$app->controller->newsModule->hideDataRimozioneView;

$queryParamCurrentView = Yii::$app->request->getQueryParam('currentView');

/** @var AmosNews $newsModule */
$newsModule = AmosNews::instance();

/** @var NewsController $appController */
$appController = \Yii::$app->controller;

\Yii::$app->params['hideDownload'] = AmosNews::instance()->hideDownloadRedactionIndex;

$textActionView = AmosNews::t('amosnews', 'Visualizza');

$dataProvider->setSort([
    'attributes' => [
        'titolo' => [
            'asc' => ['news.titolo' => SORT_ASC],
            'desc' => ['news.titolo' => SORT_DESC],
        ],
        'id' => [
            'asc' => ['news.id' => SORT_ASC],
            'desc' => ['news.id' => SORT_DESC],
        ],
        'status' => [
            'asc' => ['news.status' => SORT_ASC],
            'desc' => ['news.status' => SORT_DESC],
        ],
        'updated_at' => [
            'asc' => ['news.updated_at' => SORT_ASC],
            'desc' => ['news.updated_at' => SORT_DESC],
        ],
        'data_pubblicazione' => [
            'asc' => ['news.data_pubblicazione' => SORT_ASC],
            'desc' => ['news.data_pubblicazione' => SORT_DESC],
        ],

    ],
    'defaultOrder' => [
        'data_pubblicazione' => SORT_DESC
    ]
]);
?>
<div class="news-index">
    <?= $this->render('_search', ['model' => $model, 'queryParamCurrentView' => $queryParamCurrentView]); ?>
    <?= $this->render('_order', ['model' => $model, 'queryParamCurrentView' => $queryParamCurrentView]); ?>
    <?= NewsCarouselWidget::widget(); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            //'filterModel' => $model,
            'columns' => [
                'id' => [
                    'attribute' => 'id',
                    'label' => '#ID',
                    'visible' => $newsModule->enableAgid,
                ],
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
                'date_news' => [
                    'attribute' => 'date_news',
                    'label' => 'Pubblicato il',
                    'visible' => $newsModule->enableAgid,
                ],
                'news_expiration_date' => [
                    'attribute' => 'news_expiration_date',
                    'visible' => $newsModule->enableAgid,
                ],
                'newsContentType.name' => [
                    'attribute' => 'newsContentType.name',
                    'format' => 'html',
                    'label' => AmosNews::t('amosnews', 'Tipologia content type'),
                    'value' => function ($model) {
                        return $model->newsContentType->name;
                    },
                    'visible' => $newsModule->enableAgid,
                ],
                'updated_by_agid' => [
                    'attribute' => 'updated_by',
                    'label' => AmosNews::t('amosnews', 'Ultima modifica'),
                    'value' => function ($model) {
                        if ($user_profile = $model->getUserProfileByUserId($model->updated_by)) {
                            return $user_profile->nome . " " . $user_profile->cognome;
                        }
                        return '';
                    },
                    'format' => 'html',
                    'visible' => !$newsModule->enableAgid,
                ],
                'updated_at_agid' => [
                    'attribute' => 'updated_at',
                    'label' => AmosNews::t('amosnews', 'Data ultima modifica'),
                    'visible' => !$newsModule->enableAgid,
                    'value' => function($model){
                        $data = \Yii::$app->formatter->asDatetime($model->updated_at, 'php:d/m/Y H:i');
                        return Html::tag('p', $data, ['class' => 'm-t-0 m-b-0 ', 'style'=>'white-space:nowrap;']);
                    },
                    'format' => 'raw',
                ],
                'updated_by' => [
                    'attribute' => 'updated_by',
                    'value' => function ($model) {
                        if ($user_profile = $model->getUserProfileByUserId($model->updated_by)) {
                            return Html::a(
                                $user_profile->nome . " " . $user_profile->cognome,
                                ['/' . AmosAdmin::getModuleName() . '/user-profile/view', 'id' => $user_profile->id],
                                ['title' => AmosNews::t('amosnews', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $user_profile->nome . " " . $user_profile->cognome])]
                            );
                        }
                        return '';
                    },
                    'format' => 'html',
                    'visible' => $newsModule->enableAgid,
                ],
                'updated_at' => [
                    'attribute' => 'updated_at',
                    'visible' => $newsModule->enableAgid,
                ],
                'data_pubblicazione' => [
                    'label' => $hidePubblicationDate ? AmosNews::t('amosnews', 'Pubblicato il') : AmosNews::t('amosnews', 'Inizio pubblicazione'),
                    'attribute' => 'data_pubblicazione',
                    'value' => function ($model) {
                        /** @var News $model */
                        $data = (is_null($model->data_pubblicazione)) ? AmosNews::t('amosnews', 'Immediata') : Yii::$app->formatter->asDatetime($model->data_pubblicazione,'php:d/m/Y H:i');
                        return Html::tag('p', $data, ['class' => 'm-t-0 m-b-0 ', 'style'=>'white-space:nowrap;']);
                    },
                    'format' => 'raw',
                    'visible' => !$newsModule->enableAgid,
                ],
                'data_rimozione' => [
                    'label' => AmosNews::t('amosnews', 'Fine pubblicazione'),
                    'visible' => (!$hidePubblicationDate && !$hideDataRimozioneView && !$newsModule->enableAgid),
                    'attribute' => 'data_rimozione',
                    'value' => function ($model) {
                        /** @var News $model */
                        $data =  (is_null($model->data_rimozione) || $model->data_rimozione == '9999-12-31 00:00:00') ? AmosNews::t('amosnews', 'Mai') : Yii::$app->formatter->asDatetime($model->data_rimozione,'php:d/m/Y H:i');
                        return Html::tag('p', $data, ['class' => 'm-t-0 m-b-0 ', 'style'=>'white-space:nowrap;']);
                    },
                    'format' => 'raw',
                ],

                'news_categorie_id' => [
                    'attribute' => 'newsCategorie.titolo',
                    'label' => AmosNews::t('amosnews', 'Categoria'),
                    'visible' => $newsModule->showCategory,
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => $actionColumn,
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            $url = \yii\helpers\Url::current();
                            return Html::a('<span class="mdi mdi-delete"></span>', Yii::$app->urlManager->createUrl([
                                '/news/news/delete',
                                'id' => $model->id,
                                'url' => $url,
                            ]), [
                                'title' => AmosNews::t('amosnews', 'Elimina'),
                                'class' => 'btn btn-danger-inverse',
                                'style' => 'color:#a61919; border-top:1px solid #ccc',
                                'data-confirm' => AmosNews::t('amosnews','Sei sicuro di voler eliminare la notizia <strong>{titolo}</strong>?',['titolo' => $model->title]),
                            ]);
                        },
                        'validate' => function ($url, $model) {
                            /** @var News $model */
                            if (Yii::$app->getUser()->can('NewsValidate', ['model' => $model])) {
                                return ModalUtility::addConfirmRejectWithModal([
                                    'modalId' => 'validate-news-modal-id',
                                    'modalDescriptionText' => AmosNews::t('amosnews', '#VALIDATE_NEWS_MODAL_TEXT'),
                                    'btnText' => AmosIcons::show('check-circle', ['class' => '']),
                                    'btnLink' => Yii::$app->urlManager->createUrl(['/news/news/validate-news', 'id' => $model['id']]),
                                    'btnOptions' => ['title' => AmosNews::t('amosnews', 'Publish'), 'class' => 'btn btn-tools-secondary']
                                ]);
                            }
                        },
                        'reject' => function ($url, $model) {
                            /** @var News $model */
                            if (Yii::$app->getUser()->can('NewsValidate', ['model' => $model])) {
                                return ModalUtility::addConfirmRejectWithModal([
                                    'modalId' => 'reject-news-modal-id',
                                    'modalDescriptionText' => AmosNews::t('amosnews', '#REJECT_NEWS_MODAL_TEXT'),
                                    'btnText' => AmosIcons::show('minus-circle', ['class' => '']),
                                    'btnLink' => Yii::$app->urlManager->createUrl(['/news/news/reject-news', 'id' => $model['id']]),
                                    'btnOptions' => ['title' => AmosNews::t('amosnews', 'Reject'), 'class' => 'btn btn-tools-secondary']
                                ]);
                            }
                        },
                        'update' => function ($url, $model) {
                            /** @var News $model */
                            if (Yii::$app->user->can('NEWS_UPDATE', ['model' => $model])) {
                                $action = '/news/news/update?id=' . $model->id;

                                /** @var AmosNews $newsModule */
                                $newsModule = Yii::$app->getModule(AmosNews::getModuleName());
                                if($newsModule->enableOnlyRedactional) {
                                    $action .= '&redactional=1';
                                }

                                $options = ModalUtility::getBackToEditPopup(
                                    $model,
                                    News::NEWS_WORKFLOW_STATUS_VALIDATO,
                                    $action,
                                    ['class' => 'btn btn-tools-secondary', 'title' => Yii::t('amoscore', 'Modifica'), 'data-pjax' => '0']
                                );
                                return Html::a(AmosIcons::show('edit'), $action, $options);
                            }
                        },
                        'view' => function ($url, $model) use ($textActionView) {
                            $status = $model->hasWorkflowStatus() ? AmosNews::t('amosnews', $model->getWorkflowStatus()->getLabel()) : '--';

                            if ($status == 'In richiesta di pubblicazione') {
                                $textActionView = AmosNews::t('amosnews', 'Anteprima');
                            } else if ($status == 'Bozza') {
                                $textActionView = AmosNews::t('amosnews', 'Anteprima');
                            } else {
                                $textActionView = AmosNews::t('amosnews', 'Visualizza');
                            }
                            /** @var News $model */
                            $url = $model->getFullViewUrl();

                            /** @var AmosNews $newsModule */
                            $newsModule = Yii::$app->getModule(AmosNews::getModuleName());
                            if($newsModule->enableOnlyRedactional) {
                                $url .= '?redactional=1';
                            }

                            if (Yii::$app->user->can('NEWS_READ', ['model' => $model])) {
                                return Html::a(AmosIcons::show('file'), $url, [
                                    'class' => 'btn btn-tools-secondary',
                                    'title' => $textActionView
                                ]);
                            }
                        }
                    ]
                ],
            ],
            'enableExport' => true
        ],
        'exportConfig' => [
            'exportEnabled' => true,
            'exportColumns' => $appController->getExportRedactionColumns($model),
            'noExportColumns' => [],
            'enableBackgroundDownload' => AmosNews::instance()->enableBackgroundDownload,
            'backgroundDownloadOptions' => AmosNews::instance()->backgroundDownloadOptions,
        ],
        'listView' => [
            'itemView' => '_item',
        ],
        'iconView' => [
            'itemView' => '_icon',
        ],

    ]);
    ?>
</div>