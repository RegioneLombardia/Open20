<?php
/**
 * @var $model \open20\amos\documenti\models\Documenti
 */

use \open20\amos\documenti\models\Documenti;
use \open20\amos\core\views\AmosGridView;
use open20\amos\documenti\AmosDocumenti;

//se non ho parent metto validato
$statusParentFolder = 'DocumentiWorkflow/VALIDATO';
if (!empty($model->parent_id)) {
    $parentFolder = Documenti::findOne($model->parent_id);
    if ($parentFolder) {
        $statusParentFolder = $parentFolder->status;
    }
}

\open20\amos\layout\assets\SpinnerWaitAsset::register($this);
$this->registerJsVar('statusParentFolder', $statusParentFolder);
$this->registerJsVar('documentId', $model->id);
$this->registerJsVar('textdisabledDavalidare', AmosDocumenti::t('amosdocumenti', "Per pubblicare il Documento è necessario prima pubblicare o richiedere la validazione della Cartella che lo contiene"));
$this->registerJsVar('textdisabledValidato', AmosDocumenti::t('amosdocumenti', "Per pubblicare il Documento è necessario prima pubblicare la Cartella che lo contiene"));

$js = <<<JS
var selectedElements = [];
var elemValidato = document.getElementById('DocumentiWorkflow/VALIDATO');
var elemDavalidare = document.getElementById('DocumentiWorkflow/DAVALIDARE');
var elemUnpublish = document.getElementById('DocumentiWorkflow/BOZZA');

    function canValidate(){
        if($.inArray(statusParentFolder,['DocumentiWorkflow/VALIDATO']) !== -1){
            return true;
        }
        $(elemValidato)
        .attr('disabled', 'disabled')
        .attr('title', textdisabledValidato)
        .removeAttr('type');
        return false;
    }
    
     function canRequestValidate(){
        if($.inArray(statusParentFolder,['DocumentiWorkflow/VALIDATO', 'DocumentiWorkflow/DAVALIDARE']) !== -1){
            return true;
        }
        $(elemDavalidare)
        .attr('disabled', 'disabled')
        .attr('title',textdisabledDavalidare)
        .removeAttr('type');

        return false;
    }
    
    
    canValidate();
    canRequestValidate();
    
    // VALIDARE
    $(elemValidato).on('click', function(e){
        e.preventDefault();
        var text = $(this).text();
         $('#submit-publish').text(text);
         $('#publish-folder-type').val('publish');
         $('#modal-publish-folder').modal('show');
    });
    
    //RICHIEDI VALIDAZIONE
     $(elemDavalidare).on('click', function(e){
        e.preventDefault();
        var text = $(this).text();
        $('#submit-publish').text(text);
        $('#publish-folder-type').val('request_publish');
        $('#modal-publish-folder').modal('show');
    });
     
      //TOGLI DALLA PUBBLICAZIONE
     $(elemUnpublish).on('click', function(e){
        e.preventDefault();
        $('#spinner-publish-folder').show();
        var text = $(this).text();
        $('#submit-unpublish').text(text);
        $('#publish-folder-type').val('unpublish');
          $.ajax({
            url: '/documenti/documenti/modal-unpublish-folder-ajax',
            type: 'GET',
            data: { 
                id: documentId
                },
            success: function(data) {
                $('#modal-unpublish-folder .modal-body').html(data);
                $('#modal-unpublish-folder').modal('show');
                $('#spinner-publish-folder').hide();
             }
        });
    });
     
    //INVIA RICHIESTA PUBBLICAZIONE/RICHIESTA VALIDAZIONE
    $('#submit-publish').on('click', function(e){
        e.preventDefault();
        $('#modal-publish-folder').modal('hide');
        $('form').submit();
    });
    
    // TOGLI DALLA PUBBLICAZIONE ( METTI IN BOZZA)
     $('#submit-unpublish').on('click', function(e){
        e.preventDefault();
        $('#modal-unpublish-folder').modal('hide');
        $('form').submit();
    });
     
     $(document).on('change','input[name="selection[]"]', function(){
         var currentVal = $(this).val();
         if($(this).is(':checked')){
            selectedElements.push(currentVal);
         }else{
             selectedElements = $.grep(selectedElements, function(value) {
                    return value != currentVal;
             });
         }
         $('#count-selected-elem').text(selectedElements.length);
         $('#selected-documents').val(selectedElements.join(','));
         // console.log(selectedElements);
     });
     
     
     function selectUnselectElements(){
    $('input[name="selection[]').each(function(){
        if($.inArray($(this).val(),selectedElements) !== -1){
             $(this).attr('checked', true);
        }else{
             $(this).removeAttr('checked');
        }
    });
}
     
     // PAGINAZIONE GRID
$(document).on('click', '#modal-publish-folder .pagination li a', function(e){
    e.preventDefault();
      $.ajax({
            url: '/documenti/documenti/modal-publish-folder-ajax',
            type: 'GET',
            data: { 
                id: documentId,
                page: parseInt($(this).attr('data-page'))
                },
            success: function(data) {
                $('#modal-publish-folder .modal-body').html(data);
                selectUnselectElements();
                $('#count-selected-elem').text(selectedElements.length);
             }
        });
      
});
     
// $(document).on('load', function(e){
//     e.preventDefault();
//    
//      
// });


     
JS;
$this->registerJs($js);
$moduleCwh = \Yii::$app->getModule('cwh');
$actionId = \Yii::$app->controller->action->id;

if ($model->is_folder) { ?>
    <div id="spinner-publish-folder" class="loading" style="display:none"></div>
    <?= \yii\helpers\Html::hiddenInput('publishFolderType', 1, ['id' => 'publish-folder-type']) ?>
    <?= \yii\helpers\Html::hiddenInput('selectedDocuments', '', ['id' => 'selected-documents']) ?>

    <!-- MODALE PUBBLICAZIONE-->
    <div id="modal-publish-folder" class="modal fade bd-example-modal-xl" tabindex="-1"
         aria-labelledby="myExtraLargeModalLabel" data-focus-mouse="false" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">
                        <?= AmosDocumenti::t('amosdocumenti', "Pubblica cartella") ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= $this->render('_modal_publish_folder_grid', ['model' => $model]) ?>
                </div>
                <div class="modal-footer">
                    <?= \yii\helpers\Html::a(AmosDocumenti::t('amosdocumenti', 'Annulla'), ['#'], ['class' => 'btn btn-secondary pull-left', "data-dismiss" => "modal"]) ?>
                    <?= \yii\helpers\Html::a(AmosDocumenti::t('amosdocumenti', 'Pubblica'), ['#'], ['id' => 'submit-publish', 'class' => 'btn btn-primary']) ?>
                </div>

            </div>
        </div>
    </div>

    <!-- MODALE TOGLI DALLA PUBBLICAZIONE-->
    <div id="modal-unpublish-folder" class="modal fade bd-example-modal-xl" tabindex="-1"
         aria-labelledby="myExtraLargeModalLabel" data-focus-mouse="false" aria-hidden="true" style="display: none;">
        <!--    --><?php // $this->render('_modal_publish_folder_unpublish', ['model' => $model]);?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">
                        <?= AmosDocumenti::t('amosdocumenti', "Togli cartella dalla pubblicazione") ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
<!--                    --><?php //caricato via ajax?>
                </div>

                <div class="modal-footer">
                    <?= \yii\helpers\Html::a(AmosDocumenti::t('amosdocumenti', 'Annulla'), ['#'], ['class' => 'btn btn-secondary pull-left', "data-dismiss" => "modal"]) ?>
                    <?= \yii\helpers\Html::a(AmosDocumenti::t('amosdocumenti', 'Togli dalla pubblicazione'), ['#'], ['id' => 'submit-unpublish', 'class' => 'btn btn-primary']) ?>
                </div>

            </div>
        </div>
    </div>
<?php } ?>