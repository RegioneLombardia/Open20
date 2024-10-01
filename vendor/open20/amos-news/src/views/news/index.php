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
 * @var open20\amos\news\models\News $model
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\news\models\search\NewsSearch $searchModel
 * @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard
 * @var string $currentView
 */

/** @var AmosNews $newsModule */
$newsModule = AmosNews::instance();

/** @var NewsController $appController */
$appController = \Yii::$app->controller;

$actionColumnDefault = '{view}{update}{delete}';
$actionColumnToValidate = '{validate}{reject}';
$actionColumn = $actionColumnDefault;
if ($appController->action->id == 'to-validate-news') {
    $actionColumn = $actionColumnToValidate . $actionColumnDefault;
}
$hidePubblicationDate = $appController->newsModule->hidePubblicationDate;
$hideDataRimozioneView = $appController->newsModule->hideDataRimozioneView;

$queryParamCurrentView = Yii::$app->request->getQueryParam('currentView');

if (Yii::$app->user->isGuest) {
    if ($newsModule->hideDownloadIndexForGuests) {
        \Yii::$app->params['hideDownload'] = true;
    }
    if ($newsModule->hideChangeViewTypeIndexForGuests) {
        $appController->module->params['hideChangeViewType'] = true;
    }
}


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
                'immagine' => [
                    'label' => AmosNews::t('amosnews', 'Immagine'),
                    'format' => 'html',
                    'value' => function ($model) {
                        /** @var News $model */
                        $url = '/img/img_default.jpg';
                        if (!is_null($model->newsImage)) {
                            $url = $model->newsImage->getWebUrl('table_small', false, true);
                        }

                        if (is_null($model->alt)) {
                            $alt = '';
                        } else {
                            $alt = $model->alt;
                        }

                        $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => AmosNews::t('amosnews', $alt)]);

                        return Html::a($contentImage, $model->getFullViewUrl());
                    }
                ],
                'titolo',
                'created_by' => [
                    'attribute' => 'created_by',
                    'label' => AmosNews::t('amosnews', 'Pubblicato Da'),
                    'value' => function ($model) {
                        return Html::a($model->createdUserProfile->nomeCognome, ['/' . AmosAdmin::getModuleName() . '/user-profile/view', 'id' => $model->createdUserProfile->id], [
                            'title' => AmosNews::t('amosnews', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->createdUserProfile->nomeCognome])
                        ]);
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
                    'label' => $hidePubblicationDate ? AmosNews::t('amosnews', 'Pubblicato il') : AmosNews::t('amosnews', 'Pubblica dal'),
                    'attribute' => 'data_pubblicazione',
                    'value' => function ($model) {
                        /** @var News $model */
                        return (is_null($model->data_pubblicazione)) ? AmosNews::t('amosnews', 'Subito') : Yii::$app->formatter->asDate($model->data_pubblicazione);
                    },
                    'visible' => !$newsModule->enableAgid,
                ],
                'data_rimozione' => [
                    'visible' => (!$hidePubblicationDate && !$hideDataRimozioneView && !$newsModule->enableAgid),
                    'attribute' => 'data_rimozione',
                    'value' => function ($model) {
                        /** @var News $model */
                        return (is_null($model->data_rimozione) || $model->data_rimozione == '9999-12-31 00:00:00') ? AmosNews::t('amosnews', 'Mai') : Yii::$app->formatter->asDate($model->data_rimozione);
                    },
                ],
                'status' => [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        /** @var News $model */
                        return $model->hasWorkflowStatus() ? AmosNews::t('amosnews', $model->getWorkflowStatus()->getLabel()) : '--';
                    }
                ],
                'news_categorie_id' => [
                    'attribute' => 'newsCategorie.titolo',
                    'label' => AmosNews::t('amosnews', 'Categoria'),
                    'visible'=>$newsModule->showCategory,
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => $actionColumn,
                    'buttons' => [
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
                                $options = ModalUtility::getBackToEditPopup(
                                    $model,
                                    News::NEWS_WORKFLOW_STATUS_VALIDATO,
                                    $action,
                                    ['class' => 'btn btn-tools-secondary', 'title' => Yii::t('amoscore', 'Modifica'), 'data-pjax' => '0']
                                );
                                return Html::a(AmosIcons::show('edit'), $action, $options);
                            }
                        }
                    ]
                ],
            ],
            'enableExport' => true
        ],
        'exportConfig' => [
            'exportEnabled' => true,
            'noExportColumns' => ['immagine'],
            'enableBackgroundDownload' => AmosNews::instance()->enableBackgroundDownload,
            'backgroundDownloadOptions' => AmosNews::instance()->backgroundDownloadOptions,
        ],
        'listView' => [
            'itemView' => '_item',
        ],
        'iconView' => [
            'itemView' => '_icon',
        ],
        'viewIconClass' => 'open20\amos\core\views\ExtendedIconView'
    ]);
    ?>
</div>