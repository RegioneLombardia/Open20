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
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\news\models\search\NewsSearch $searchModel
 * @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard
 * @var string $currentView
 */

\open20\amos\layout\assets\LoadingSpinnerAsset::register($this);

$queryParamCurrentView = Yii::$app->request->getQueryParam('currentView');

/** @var NewsController $appController */
$appController = \Yii::$app->controller;

// Hide download button
Yii::$app->params['hideDownload'] = true;
// Hide order button
$appController->module->params['orderParams']['news']['enable'] = false;
// Hide filters button
$appController->module->params['searchParams']['news']['enable'] = false;

$textActionView = AmosNews::t('amosnews', 'Visualizza');

$dataProvider->setSort([
    'attributes' => [
        'titolo' => [
            'asc' => ['news.titolo' => SORT_ASC],
            'desc' => ['news.titolo' => SORT_DESC],
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

$jsUpdateGridView = <<<JS

    var currentRequest = null;
    var gridViewContainer = $('.clone-news-index .grid-view');
    var scrollAnchor = $('.clone-news-index');
    var searchInput = $('#search-title-textinput');
    
    searchInput.on('keyup', function () {
        var title = searchInput.val();
        
        // Annulla la richiesta precedente se presente
        if (currentRequest) {
            currentRequest.abort();
        }
        
        currentRequest = $.ajax({
            url: "/news/news/update-grid-view",
            dataType: 'json',
            data: {
                title: title,
                page: 0
            },
            beforeSend: function() {
                renderLoadingSpinner();
            },
            success: function(data) {
                gridViewContainer.html(data);
            }
        });
    });
    
    // Pagination ajax
    $(document).on('click', 'ul.pagination li a', function(e) {
        e.preventDefault();
        scrollToAnchor(scrollAnchor);
        var page = $(this).attr('data-page');
        
        // Annulla la richiesta precedente se presente
        if (currentRequest) {
            currentRequest.abort();
        }
        
        setTimeout(function() {
            currentRequest = $.ajax({
                url: "/news/news/update-grid-view",
                dataType: 'json',
                data: {
                    title: searchInput.val(),
                    page: page
                },
                beforeSend: function() {
                    renderLoadingSpinner();
                },
                success: function(data) {
                    gridViewContainer.html(data);
                }
            });
        }, 600);
    });
    
    $('#cancel-search-btn').on('click', function(e) {
        e.preventDefault();
        if (!searchInput.val()) {
            return;
        }
        searchInput.val('');
        searchInput.trigger('keyup');
    });
    
    function renderLoadingSpinner() {
        var loadingSpinnerHtml = '<div class="text-center"><span class="loading-spinner m-t-25"></span></div>';
        var isPresentLoadingSpinner = $('.clone-news-index .grid-view .loading-spinner').length;
        
        if (!isPresentLoadingSpinner) {
            gridViewContainer.html(loadingSpinnerHtml);
        }
    }
    
    function scrollToAnchor(scrollAnchor) {
        $('html, body').animate({
            scrollTop: scrollAnchor.offset().top - 200
        }, 500);
    }

JS;

$this->registerJs($jsUpdateGridView);

?>


<div class="clone-news-index">
    <div class="row m-b-10">
        <div class="col-xs-6">
            <?= Html::textInput('search-title-textinput', null, [
                'class' => 'form-control',
                'id' => 'search-title-textinput',
                'placeholder' => AmosNews::t('amosnews', 'Ricerca per titolo...'),
                'style' => 'height: 37px;'
            ]); ?>
        </div>
            <?= Html::button('<span class="mdi mdi-close"></span>', [
                'class' => 'btn btn-xs btn-danger-inverse',
                'id' => 'cancel-search-btn',
                'title' => AmosNews::t('amosnews', 'Annulla ricerca'),
            ]) ?>
    </div>


    <div class="grid-view">
        <?= $this->render('parts/_gridView', [
            'dataProvider' => $dataProvider,
            'currentView' => $currentView,
        ]) ?>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?= Html::a(AmosNews::t('amosnews', 'Annulla'), ['/community/join/open-join', 'id' => Yii::$app->request->get('communityId')], [
                    'class' => 'btn btn-secondary pull-left m-t-15'
            ]); ?>
        </div>
    </div>

</div>