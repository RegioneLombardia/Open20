<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\news\AmosNews;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\editors\maps\MapViewWidget;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\news\models\search\NewsSearch $model
 * @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard
 * @var string $currentView
 */

$selectPeriodJs = <<<JS

    $('#select-period').on('change', function() {
        var dataPubblicazioneFromContainer = $('#data_pubblicazione_from_container');
        var dataPubblicazioneToContainer = $('#data_pubblicazione_to_container');
        var dataPubblicazioneFrom = $('#data_pubblicazione_from-id');
        var dataPubblicazioneTo = $('#data_pubblicazione_to-id');
        var dataPubblicazioneFromDisp = $('#data_pubblicazione_from-id-disp');
        var dataPubblicazioneToDisp = $('#data_pubblicazione_to-id-disp');
        var val = $(this).val();
        if(val == 'custom') {
            dataPubblicazioneFromContainer.show();
            dataPubblicazioneToContainer.show();
        } else {
            dataPubblicazioneFromContainer.hide();
            dataPubblicazioneToContainer.hide();
            dataPubblicazioneFrom.val('');
            dataPubblicazioneTo.val('');
            dataPubblicazioneFromDisp.val('');
            dataPubblicazioneToDisp.val('');
        }
    });

JS;

$this->registerJs($selectPeriodJs);

$url = '/news/' . \Yii::$app->controller->id . '/' . \Yii::$app->controller->action->id;

$this->title = AmosNews::t('amosnews', 'Notizie con punto di interesse');
$this->params['breadcrumbs'][] = $this->title;
$this->params['forceBreadcrumbs'][] = [
    'label' => AmosNews::t('amosnews', 'News'),
    'url' => $url,
];
$this->params['forceBreadcrumbs'][] = [
    'label' => $this->title,
];

/** @var AmosNews $newsModule */
$newsModule = AmosNews::instance();
$queryParams = Yii::$app->request->getQueryParams();
$searchParams = $queryParams['NewsSearch'];
$markers = [];


$models = $dataProvider->getModels();
?>

<?php
$form = \yii\widgets\ActiveForm::begin([
    'id' => 'form-map-search',
    'method' => 'get',
    'options' => [
        'class' => 'form'
    ]
]); ?>


<div class="map-index">

    <div class="row">

        <div class="col-xs-12 col-lg-4 col-md-4">
            <?= $form->field($model, 'period')->widget(Select2::className(), [
                'data' => [
                    'week' => AmosNews::t('amosnews', 'Ultima settimana'),
                    'month' => AmosNews::t('amosnews', 'Ultimo mese'),
                    '3months' => AmosNews::t('amosnews', 'Ultimi 3 mesi'),
                    'year' => AmosNews::t('amosnews', 'Ultimo anno'),
                    'custom' => AmosNews::t('amosnews', 'Personalizzato'),
                ],
                'hideSearch' => true,
                'options' => [
                    'value' => empty($searchParams['period']) ? $model::DEFAULT_PERIOD_MAP_SEARCH : $searchParams['period'],
                    'id' => 'select-period',
                    'placeholder' => AmosNews::t('amosnews', 'Seleziona periodo...'),
                    'multiple' => false
                ]
            ]); ?>
        </div>

        <div class="col-xs-12 col-lg-3 col-md-4" id="data_pubblicazione_from_container" style="<?= $searchParams['period'] != 'custom' ? 'display: none' : '' ?>">
            <?= $form->field($model, 'data_pubblicazione_from')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'displayFormat' => 'php:d/m/Y',
                'saveFormat' => $newsModule->dateFormatNews == DateControl::FORMAT_DATETIME ? 'php:Y-m-d H:i:s' : 'php:Y-m-d',
                'options' => [
                    'id' => 'data_pubblicazione_from-id',
                    'placeholder' => AmosNews::t('amosnews', 'Data inizio'),
                    'class' => 'form-control'
                ],
            ])->label(AmosNews::t('amosnews', 'Data inizio')); ?>
        </div>

        <div class="col-xs-12 col-lg-3 col-md-4" id="data_pubblicazione_to_container" style="<?= $searchParams['period'] != 'custom' ? 'display: none' : '' ?>">
            <?= $form->field($model, 'data_pubblicazione_to')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'displayFormat' => 'php:d/m/Y',
                'saveFormat' => $newsModule->dateFormatNews == DateControl::FORMAT_DATETIME ? 'php:Y-m-d 23:59:59' : 'php:Y-m-d',
                'options' => [
                    'id' => 'data_pubblicazione_to-id',
                    'placeholder' => AmosNews::t('amosnews', 'Data fine'),
                    'class' => 'form-control'
                ],
            ])->label(AmosNews::t('amosnews', 'Data fine')); ?>
        </div>

        <div class="col-xs-12 col-lg-2 m-b-20">
            <?= Html::a(AmosIcons::show('close'), \yii\helpers\Url::to([$url, 'map' => true]), [
                'class' => 'btn btn-outline-danger filter-map',
                'title' => AmosNews::t('amosnews', 'Annulla')
            ]); ?>
            <?= Html::submitButton(AmosIcons::show('search', ['class' => 'm-r-5']) . AmosNews::t('amosnews', 'Cerca'), [
                'class' => 'btn btn-primary filter-map',
                'title' => AmosNews::t('amosnews', 'Cerca')
            ]); ?>
        </div>

    </div>

</div>

<?php \yii\widgets\ActiveForm::end(); ?>

<?php
foreach ($models as $newsModel) {
    /** @var \open20\amos\news\models\News $newsModel */
    $marker['titolo'] = $newsModel->titolo;
    $marker['place_id'] = $newsModel->place_id;

    $url = '/img/img_default.jpg';
    if (!is_null($newsModel->getNewsImage())) {
        $url = $newsModel->getNewsImage()->getWebUrl('item_news', false, true);
    }
    $marker['immagine'] = $url;
    $marker['link'] = $newsModel->getFullViewUrl();

    $markers[] = $marker;
}

$cardTemplate = '<a href="#link" class="marker-card-link" title="Vai a #title" style="display:flex">';
$cardTemplate .= '<img src="#img" style="max-width:70px;max-height:70px" alt="#title">';
$cardTemplate .= '<div class="marker-card-info" style="padding-left:15px"><h6>#title</h6></div>';
$cardTemplate .= '</a>';
?>

<?= MapViewWidget::widget([
    'markers' => $markers,
    'styles' => MapViewWidget::NoPointStyle,
    'cardTemplate' => $cardTemplate,
    'attributeName' => 'place_id'
    //'icona'=>\frontend\widgets\maps\MapView::BWPointer
]); ?>
