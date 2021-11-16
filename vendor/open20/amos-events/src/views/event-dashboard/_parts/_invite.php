<?php

use open20\amos\events\AmosEvents;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use \yii\bootstrap4\ActiveForm;
// use open20\amos\core\forms\ActiveForm;
use open20\amos\events\models\search\UserEventSearch;
use yii\helpers\Html;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

/** @var \open20\amos\events\models\search\EventTypeSearch $eventTypeSearchModel */
$eventTypeSearchModel = $moduleEvents->createModel('EventTypeSearch');
$types = $eventTypeSearchModel::searchGenericContextEventTypes()->all();
$moduleCwh = \Yii::$app->getModule('cwh');
$eventId = $model->id;
$scope = null;
if (!empty($moduleCwh)) {
    $scope = $moduleCwh->getCwhScope();
}

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
                url: '/events/wizard/render-search-ajax',
                data: {
                    'data': $('#form-search').serialize(),
                    'plus': plus,
                    },
                method: 'post'
            })
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
               url: '/events/wizard/search-users',
               type: 'post',
               data: {
                    'data': $('#form-search').serialize(),
                    'wizard': '$wizard'
               },
               success: function (data) {
                   $('.loading').hide();
                  $('#result-search-container').html(data);
               }
           });
     });
     
      // function search
     $(document).on('click', '#save-list',function(e){
         e.preventDefault();
           $.ajax({
               url: '/events/wizard/save-list',
               type: 'post',
               data: {
                    'data': $('#form-result').serialize(),
                    'searchParams' : $('#result-search-params').val()
               },
               success: function (data) {
                   if(data['success'] == true){
                       $('#save-inputs').html("<div class='alert alert-success'>"+data['message']+"</div>");
                       // document.location.href = '/events/wizard/invite?id=';
                 }
                 else {
                       alert(data['message']);
                 }
               }
           });
     });
     
     $('input[name="UserEventSearch[type]"]').click(function(){
          var value = $("input[name='UserEventSearch[type]']:checked").val();
          console.log(value);
          if(value == 2){
              $('#search-tags').show();
          }
          else {
              $('#search-tags').hide();
          }
     });
     
     $(document).on('click', '#form-result button[type="submit"]', function(){
         $("#form-result").yiiActiveForm("validate");
     });

JS;
$this->registerJs($js);
//            pr($internalList->getErrors());
//            pr($model->getErrors());

$modelSearch->event_id = $model->id

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

        <div class="py-4 d-flex title-substeps affix-top">
            <div class="mr-2">
                <svg class="icon">
                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_contact_mail"></use>
                </svg>
            </div>

            <h5 class="font-weight-bold ">
            <?= AmosEvents::t('amosevents', "Ricerca utenti da invitare") ?>
            <a href="#" data-toggle="tooltip" data-html="true" title="<?= AmosEvents::t('amosevents', "Ricerca gli utenti da invitare all'evento e assegna un titolo alla tua ricerca. Puoi invitare solo gli utenti già presenti nel tuo database. <br><br><i>nota:</i> Il database delle Direzioni Generali raccoglie gli utenti importati e gli utenti registrati ad un evento precedente.") ?>">
                <svg class="icon icon-xs icon-secondary mb-2">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_help"></use>
                </svg>
            </a>
            </h5>
        </div>
        

        <div class="row variable-gutters">
            <div class="col-md-12">
                <?= $form->field($modelSearch, 'type')->radioList([
                    UserEventSearch::SEARCH_TYPE_ALL => AmosEvents::t('amosevents', 'All users'),
                    UserEventSearch::SEARCH_TYPE_LIST_TAG => AmosEvents::t('amosevents', 'Tag di Lista Evento')
                ])->label(false);
                ?>

                <?php
                $displayNone = 'display:none';
                if ($modelSearch->type == UserEventSearch::SEARCH_TYPE_LIST_TAG) {
                    $displayNone = '';
                }
                if (empty($modelSearch->registered_invited)) {
                    $modelSearch->registered_invited = 0;
                }?>
                <div id="search-tags" style="<?= $displayNone ?>">
                    <div>
                        <?= $form->field($modelSearch, 'registered_invited')->radioList([
                            0 => AmosEvents::t('amosevents', 'Utenti invitati ad eventi con i seguenti Tag'),
                            2 => AmosEvents::t('amosevents', 'Utenti registrati ad eventi con i seguenti Tag')
                        ])->label(false) ?>

                        <?php
                        $searchTags = [];
                        foreach (\open20\amos\tag\models\Tag::find()->andWhere(['id' => $modelSearch->searchTags])->all() as $tag){
                            $searchTags[$tag->id] =  $tag->nome;
                        }
                        ?>
                        <?= $form->field($modelSearch, 'searchTags')->widget(
                            Select2::className(),
                            [
                                'data' => (!empty($modelSearch->searchTags) ? $searchTags : []),
                                'options' => ['multiple' => true, 'placeholder' => AmosEvents::t('amosevents', 'Cerca tra i Tag di Lista Evento...')],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 1,
                                    'ajax' => [
                                        'url' => \yii\helpers\Url::to(['/events/wizard/ajax-event-tag-list', 'id' => $model->id]),
                                        'dataType' => 'json',
                                        'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                ],
                            ]
                        )->label(AmosEvents::t('amosevents', "Tag di Lista Evento")); ?>
                    </div>
                </div>
                <?php \yii\widgets\Pjax::begin(['id' => 'search-container', 'timeout' => 2000]);

                $count = 1;
                if (!empty($modelSearch['field']) && count($modelSearch['field']) > 0) {
                    $count = count($modelSearch['field']);
                }
                ?>
                <?= $this->render('@vendor/open20/amos-events/src/views/wizard/_search_users', ['modelSearch' => $modelSearch, 'form' => $form, 'count' => $count]) ?>
                <?php \yii\widgets\Pjax::end(); ?>

                <div class="my-5">
                    <?= Html::a(AmosEvents::t('amosevents', "+ AGGIUNGI REGOLA"), [], ['id' => 'btn-add-rule']) ?>
                </div>
                <div class="mb-5">
                    <?= Html::a(AmosEvents::t('amosevents', "Ricerca tra gli utenti del tuo database"), [], ['id' => 'btn-search-users', 'class' => 'btn btn-sm btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php $modelSearch->event_id = $model->id;?>
    <?= $form->field($modelSearch, 'event_id')->hiddenInput()->label(false) ?>
    <?php ActiveForm::end(); ?>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'form-result']
    ]); ?>
    <div id="result-search-container">
        <?php
        if (!$internalList->isNewRecord || !empty(\Yii::$app->request->post('EventInternalList')['search_params'])) {
            echo $this->render('@vendor/open20/amos-events/src/views/wizard/_results_search', [
                'internalList' => $internalList,
                'modelSearch' => $modelSearch,
                'form' => $form,
                'count' => $internalList->n_user_found,
                'saveAjax' => ($wizard || $saveButton) ? false : true,
            ]);
        } ?>
    </div>

    <?php

    if ($wizard == true) { ?>
        <div class="buttons row">
            <?php echo WizardPrevAndContinueButtonWidget::widget([
                'model' => $model,
                'viewPreviousBtn' => true,
                'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/events/wizard/emails', 'id' => $model->id]),

                'cancelUrl' => '',

            ]) ?>
        </div>
    <?php } else if (!empty($saveButton) && $saveButton == true) { ?>
        <div class="buttons">
            <?= Html::a(AmosEvents::t('amosevents', 'Back'),['/events/event-dashboard/invite', 'id' => $model->id], ['class' => 'btn btn-secondary pull-left']) ?>
            <?= Html::submitButton(AmosEvents::t('amosevents', 'Save'), ['class' => 'btn btn-primary float-right']) ?>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>

</div>