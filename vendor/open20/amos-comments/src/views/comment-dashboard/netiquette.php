<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-comments/src/views
 */

use open20\amos\comments\AmosComments;
use open20\amos\comments\utility\CommentsUtility;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\views\DataProviderView;
use kartik\widgets\SwitchInput;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Comment Dashboard';
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/comments']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['forceBreadcrumbs'][] = ['label' => AmosComments::t('amoscomments', 'Netiquette')];

\open20\amos\layout\assets\LoadingSpinnerAsset::register($this);

$models = $dataProvider->getModels();
$model = $models[0];


$jsSwitchInputChange = <<<JS
    function switchInputChange(e) {
        $.ajax({
            url: '/comments/comment-dashboard/update-ajax',
            method: 'GET',
            data: {
                id: '{$model->id}',
                attribute: e.target.id,
                value: e.target.checked
            },
            success: function(response) {
                // console.log(response);
            },
            error: function(error) {
                alert('Si è verificato un errore durante il salvataggio.');
                console.log(error);
            }
        });
    }
JS;

$jsSearchWord = <<<JS

    // Hide change view div! Comment this if you add additional buttons or filter configs!
    $('.container-change-view').hide();

    var currentRequest = null;
    var gridViewContainer = $('.comment-dashboard-index .grid-view');
    var scrollAnchor = $('.comment-dashboard-index .anchor');
    var searchInput = $('#search-word');
    
    searchInput.on('keyup', function () {
        var word = searchInput.val();
        
        // Annulla la richiesta precedente se presente
        if (currentRequest) {
            currentRequest.abort();
        }
        
        currentRequest = $.ajax({
            url: "/comments/comment-dashboard/update-netiquette-grid-view",
            dataType: 'json',
            data: {
                word: word,
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
                url: "/comments/comment-dashboard/update-netiquette-grid-view",
                dataType: 'json',
                data: {
                    word: searchInput.val(),
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
        var isPresentLoadingSpinner = $('.comment-dashboard-index .grid-view .loading-spinner').length;
        
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

$this->registerJs($jsSearchWord);

?>

<div class="comment-dashboard-index">

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'id' => 'comment-dashboard_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>

    <div class="row">
        <div class="col-xs-12">
            <h4 class="text-uppercase"><?= AmosComments::t('amoscomments', 'Amministra Netiquette') ?></h4>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="col-xs-12">
            <div class="subcommunity-section" style="padding:24px 24px 0 24px">
                <div class="row">
                    <div class="col-xs-10 form-group m-b-0">
                        <?= Html::label(
                            AmosComments::t('amoscomments', 'Attivare la Netiquette sui commenti della piattaforma?'),
                            'netiquette_platform',
                            ['class' => 'control-label m-t-15']
                        ); ?>
                        <span class="mdi mdi-information-outline" data-toggle="tooltip" title="<?= AmosComments::t('amoscomments', 'Plugin coinvolti:<br>{plugins}.', ['plugins' => CommentsUtility::getModerationInvolvedPlugins()]) ?>"></span>
                    </div>
                    <div class="col-xs-2">
                        <?= $form->field($model, 'netiquette_platform')->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                'onColor' => 'success',
                                'onText' => BaseAmosModule::t('amoscore', 'Sì'),
                                'offText' => BaseAmosModule::t('amoscore', 'No'),
                            ],
                            'options' => ['id' => 'netiquette_platform'],
                            'pluginEvents' => [
                                "switchChange.bootstrapSwitch" => new \yii\web\JsExpression($jsSwitchInputChange),
                            ]
                        ])->label(false); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="subcommunity-section" style="padding:24px 24px 0 24px">
                <div class="row">
                    <div class="col-xs-10 form-group m-b-0">
                        <?= Html::label(
                            AmosComments::t('amoscomments', 'Le Community supportano la Netiquette?'),
                            'netiquette_communities',
                            ['class' => 'control-label m-t-15']
                        ); ?>
                        <span class="mdi mdi-information-outline" data-toggle="tooltip" title="<?= AmosComments::t('amoscomments', 'Se attivato, consente al Community Manager di attivare o disattivare la funzionalità all\'interno delle Community') ?>"></span>
                    </div>
                    <div class="col-xs-2">
                        <?= $form->field($model, 'netiquette_communities')->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                'onColor' => 'success',
                                'onText' => BaseAmosModule::t('amoscore', 'Sì'),
                                'offText' => BaseAmosModule::t('amoscore', 'No'),
                            ],
                            'options' => ['id' => 'netiquette_communities'],
                            'pluginEvents' => [
                                "switchChange.bootstrapSwitch" => new \yii\web\JsExpression($jsSwitchInputChange),
                            ]
                        ])->label(false); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


    <hr class="m-b-15 m-t-20" style="border-top: 1px solid #CCC">
    <div class="anchor"></div>

    <div class="row">
        <div class="col-xs-6">
            <h4 class="text-uppercase"><?= AmosComments::t('amoscomments', 'Blacklist commenti') ?></h4>
        </div>
        <div class="col-xs-6 text-right m-t-5">
            <?= Html::a('<span class="mdi mdi-upload m-r-5"></span><small>' . AmosComments::t('amoscomments', 'Importa termini') . '</small>',
                ['/comments/comment-blacklist/import'],
                [
                    'class' => 'btn btn-secondary',
                    'title' => AmosComments::t('amoscomments', 'Importa termini da file excel'),
                ]); ?>
            <?= Html::a('<span class="mdi mdi-download m-r-5"></span><small>' . AmosComments::t('amoscomments', 'Esporta blacklist') . '</small>',
                ['/comments/comment-blacklist/export'],
                [
                    'class' => 'btn btn-secondary',
                    'title' => AmosComments::t('amoscomments', 'Esporta blacklist in file excel'),
                ]); ?>
            <?= Html::a('<span class="mdi mdi-plus-circle-outline m-r-5"></span><small>' . AmosComments::t('amoscomments', 'Aggiungi termine') . '</small>',
                ['/comments/comment-blacklist/create'],
                [
                    'class' => 'btn btn-primary',
                    'title' => AmosComments::t('amoscomments', 'Aggiungi un nuovo termine alla blacklist'),
                ]); ?>
        </div>
    </div>

    <div class="row m-t-15">
        <div class="col-xs-3">
            <div class="form-group">
                <!--                --><?php //= Html::label('<small>' . AmosComments::t('amoscomments', 'Cerca termine') . '</small>', 'search-word', [
                //                    'class' => 'control-label text-uppercase'
                //                ]); ?>
                <?= Html::textInput('searchWord', null, [
                    'class' => 'form-control',
                    'id' => 'search-word',
                    'placeholder' => AmosComments::t('amoscomments', 'Cerca termine...'),
                    'style' => 'height: 37px;'
                ]); ?>
            </div>
        </div>
        <?= Html::button('<span class="mdi mdi-close"></span>', [
            'class' => 'btn btn-xs btn-danger-inverse',
            'id' => 'cancel-search-btn',
            'title' => AmosComments::t('amoscomments', 'Annulla ricerca'),
        ]); ?>
    </div>

    <div class="grid-view" style="margin-left: 5px">
        <?= $this->render('@vendor/open20/amos-comments/src/views/comment-blacklist/parts/gridView', [
            'dataProvider' => $blacklistDataProvider,
            'currentView' => $currentView
        ]); ?>
    </div>

</div>