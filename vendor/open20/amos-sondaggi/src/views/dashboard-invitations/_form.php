<?php

use open20\amos\sondaggi\AmosSondaggi;
//use open20\amos\sondaggi\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use open20\amos\core\forms\ActiveForm;
use open20\amos\sondaggi\models\search\SondaggiInvitationsSearch;
use yii\helpers\Html;
use open20\amos\core\forms\CloseSaveButtonWidget;

//$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

/** @var \open20\amos\events\models\search\EventTypeSearch $eventTypeSearchModel */

if (empty($model->type)) $model->type = 0;
if (empty($model->filter_type)) $model->filter_type = 0;

$this->registerCss("
#errore-alert-common {display:none;}
");

$js = <<<JS
    $('#switch-settings').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
            $('#settings-div').show();
        }
        else {
           $('#settings-div').hide();
        }
    });

    function realoadSearch(container, plus){
           $.pjax.reload({
                container: container,
                timeout: 20000,
               replace: false,
                url: '/sondaggi/dashboard-invitations/render-search-ajax',
                data: {
                    'data': $('#form-search').serialize(),
                    'plus': plus,
                    },
                method: 'post'
            }).done(function() {
                $('#filter-value').change();
            });
    }

    $(document).on('click','#btn-add-rule',function(e){
        e.preventDefault();
        realoadSearch('#search-container', 1);
    });

    $(document).on('click', '#btn-remove-rule',function(e){
        e.preventDefault();
        var value = $(this).attr('data');
        $('#row-search-'+value).remove();
        realoadSearch('#search-container', 0);
    });

    // function search
     $('#btn-search-users').click(function(e){
         e.preventDefault();
         $('.loading').show();
           $.ajax({
               url: '/sondaggi/dashboard-invitations/search-invited',
               type: 'post',
               data: {
                    'data': $('#form-search').serialize()
               },
               success: function (data) {
                   $('.loading').hide();
                  $('#result-search-container').html(data);
               }
           });
     });

     $('input[name="SondaggiInvitations[type]"]').click(function(){
          var value = $("input[name='SondaggiInvitations[type]']:checked").val();
          if(value == 1){
              $('#search-tags').show();
          }
          else {
              $('#search-tags').hide();
          }
     });

     $('input[name="SondaggiInvitations[filter_type]"]').click(function(){
           var value = $("input[name='SondaggiInvitations[filter_type]']:checked").val();
           if(value == 0){
               $('#filter-groups').show();
               $('#filter-tags').hide();
           }
           else {
               $('#filter-groups').hide();
               $('#filter-tags').show();
           }
      });

     $(document).on('click', '#form-result button[type="submit"]', function(){
         $("#form-result").yiiActiveForm("validate");
     });

JS;
$this->registerJs($js);
//            pr($internalList->getErrors());
//            pr($model->getErrors());

//$modelSearch->event_id = $model->id

?>

<!-- SELECT 2 PLACEHOLDER FIX -->
<style>
.select2-container .select2-search--inline {
    width: 100%;
}
.select2-container .select2-search--inline > input {
    min-width: 100% !important;
}
</style>

<div class="dimmable position-fixed loader loading" style="display:none">
    <div class="dimmer d-flex align-items-center" id="dimmer1">
        <div class="dimmer-inner">
            <div class="dimmer-icon">
                <div class="progress-spinner progress-spinner-active loading m-auto">
                    <span class="sr-only">Caricamento...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="utenti">
    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'form-search']
    ]); ?>
    <div>

        <div class="p-t-20 p-b-20 d-flex title-substeps affix-top">
            <h5 class="font-weight-bold ">
            <!-- <span class="m-r-10">< ?= Amosicons::show('address-card', [], 'dash') ?></span> -->
            <?= AmosSondaggi::t('AmosSondaggi', "#search_organizations_invitation") ?>
            </h5>
        </div>


        <div class="row variable-gutters">
            <div class="col-md-12">
                <?=$form->field($model, 'type')->radioList([
                    SondaggiInvitationsSearch::SEARCH_ALL => AmosSondaggi::t('AmosSondaggi', '#all_organizations'),
                    SondaggiInvitationsSearch::SEARCH_FILTER => AmosSondaggi::t('AmosSondaggi', '#by_filter')
                ])->label(false);
                ?>

                <?php
                $displayNone = 'display:none';
                if ($model->type == SondaggiInvitationsSearch::SEARCH_FILTER) {
                    $displayNone = '';
                }
                ?>
                <div id="search-tags" style="<?= $displayNone ?>">
                    <div>
                        <?= $form->field($model, 'filter_type')->radioList([
                            SondaggiInvitationsSearch::FILTER_GROUPS => AmosSondaggi::t('AmosSondaggi', '#organization_groups'),
                            SondaggiInvitationsSearch::FILTER_INVITED_TAG => AmosSondaggi::t('AmosSondaggi', '#polls_invited_to_tags'),
                            SondaggiInvitationsSearch::FILTER_COMPILED_TAG => AmosSondaggi::t('AmosSondaggi', '#polls_compiled_to_tags')
                        ])->label(false) ?>

                        <?php
                        // Rendering group search...
                        $displayNone = 'display:none';
                        if ($model->filter_type == SondaggiInvitationsSearch::FILTER_GROUPS) {
                            $displayNone = '';
                        }
                        $groupsList = $model->getGroups()->all();
                        $groups = ArrayHelper::map($groupsList, 'id', 'name');
                        ?>
                        <span id="filter-groups" style="<?=$displayNone?>">
                            <?= $form->field($model, 'search_groups')->widget(
                                Select2::className(),
                                [
                                    'data' => $groups,
                                    'options' => [
                                        'multiple' => true,
                                        'placeholder' => AmosSondaggi::t('AmosSondaggi', '#search_groups'),
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 1,
                                        'ajax' => [
                                            'url' => \yii\helpers\Url::to(['/sondaggi/dashboard-invitations/group-list', 'id' => $model->id]),
                                            'dataType' => 'json',
                                            'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                                        ],
                                    ],
                                ]
                            )->label(AmosSondaggi::t('AmosSondaggi', "#poll_group")); ?>
                        </span>
                        <?php
                        // Rendering tag search...
                        $displayNone = 'display:none';
                        if ($model->filter_type == SondaggiInvitationsSearch::FILTER_INVITED_TAG || $model->filter_type == SondaggiInvitationsSearch::FILTER_COMPILED_TAG) {
                            $displayNone = '';
                        }
                        $search_tags = [];
                        foreach (SondaggiInvitationsSearch::getPollTags()->andWhere(['id' => $model->search_tags])->all() as $tag){
                            $search_tags[$tag->id] =  $tag->nome;
                        }
                        ?>
                        <span id="filter-tags" style="<?=$displayNone?>">
                            <?= $form->field($model, 'search_tags')->widget(
                                Select2::className(),
                                [
                                    'data' => (!empty($model->search_tags) ? $search_tags : []),
                                    'options' => [
                                        'multiple' => true,
                                        'placeholder' => AmosSondaggi::t('AmosSondaggi', '#search_tags'),
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 1,
                                        'ajax' => [
                                            'url' => \yii\helpers\Url::to(['/sondaggi/dashboard-invitations/tag-list', 'id' => $model->id]),
                                            'dataType' => 'json',
                                            'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                                        ],
                                    ],
                                ]
                            )->label(AmosSondaggi::t('AmosSondaggi', "#poll_tag")); ?>
                        </span>
                    </div>
                </div>
                <?php \yii\widgets\Pjax::begin(['id' => 'search-container', 'timeout' => 2000]);

                $count = 1;
                if (!empty($model['field']) && count($model['field']) > 0) {
                    $count = count($model['field']);
                }
                ?>
                <?= $this->render('@vendor/open20/amos-sondaggi/src/views/dashboard-invitations/_search_params', ['model' => $model, 'form' => $form, 'count' => $count]) ?>
                <?php \yii\widgets\Pjax::end(); ?>

                <div class="m-b-30">
                    <?= Html::a(AmosSondaggi::t('AmosSondaggi', "#add_rule"), [], ['id' => 'btn-add-rule']) ?>
                </div>
                <div class="m-b-30">
                    <?= Html::a(AmosSondaggi::t('AmosSondaggi', "#search_organizations"), [], ['id' => 'btn-search-users', 'class' => 'btn btn-sm btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

    <div id="result-search-container">

    </div>

    <div>
        <?= $form->field($model, 'name')->textInput(['placeholder' =>  AmosSondaggi::t('AmosSondaggi', 'Assegna un titolo alla tua ricerca')])->label(AmosSondaggi::t('AmosSondaggi', 'Titolo della ricerca')) ?>
    </div>

        <div id="form-actions" class="bk-btnFormContainer">
            <?=
            CloseSaveButtonWidget::widget([
                'model' => $model,
                'buttonNewSaveLabel' => $model->isNewRecord ? AmosSondaggi::t('amossondaggi', 'Inserisci') : AmosSondaggi::t('amossondaggi',
                    'Salva'),
                'closeButtonLabel' => AmosSondaggi::t('amossondaggi', 'Chiudi'),
            ]);
            ?>
        </div>

    <?php //$modelSearch->event_id = $model->id;?>
    <?= $form->field($model, 'sondaggi_id')->hiddenInput()->label(false) ?>
    <?php ActiveForm::end(); ?>

</div>