<?php
$scope = \open20\amos\cwh\AmosCwh::getInstance()->getCwhScope();
$scopeFilter = (empty($scope))? false : true;
if(!$scopeFilter) {
    $this->registerJs(<<<JS
    var resetTag = function(tag) {
         tag.removeClass('focused');
         tag.find('.red').remove();
    };

    var requiredTag = function(tag) {
         tag.addClass('focused');
         
         if(tag.find('.red').length == 0) {
             tag.find('.tags-title').append('<span class=\"red\">*</span>');
         }        
    };
        
    var resetRecipients = function(recipientsInput, recipientsWrap) {
         recipientsInput.prop('disabled', true);
         recipientsInput.val(null);
         
         recipientsWrap.removeClass('focused');
         recipientsWrap.find('.red').remove();
    };
    
    var requiredRecipients = function(recipientsInput, recipientsWrap) {
        recipientsInput.prop('disabled', false);
        recipientsWrap.addClass('focused');
        
        if(recipientsWrap.find('.red').length == 0) {
             recipientsWrap.find('label').append('<span class=\"red\">*</span>');
         }       
    };   


//    $('#cwh-regola_pubblicazione').on('change', function(){
//        recipientsInput = $('#cwh-destinatari');
//        recipientsWrap = $('.field-cwh-destinatari');
//        tag = $('#amos-tag');
//        $('.tags-title').addClass('note_asterisk');
//        recipientsWrap.find('label').addClass('note_asterisk');
//    
//         if($(this).val() == 1 ) {
//            resetRecipients(recipientsInput, recipientsWrap);
//            resetTag(tag);
//         }
//         
//          if($(this).val() == 2){
//            resetRecipients(recipientsInput, recipientsWrap);           
//            requiredTag(tag);
//          }
//          
//           if($(this).val() == 3){
//            requiredRecipients(recipientsInput, recipientsWrap);
//            resetTag(tag);
//           }
//           
//           if($(this).val() == 4){
//            resetRecipients(recipientsInput, recipientsWrap);
//            resetTag(tag);
//             
//            setTimeout(function(){
//                requiredRecipients(recipientsInput, recipientsWrap);
//                requiredTag(tag);
//            }, 300);         
//           }
//    });

JS
);

}
?>
<?=
open20\amos\cwh\widgets\Cwh3ColsWidget::widget([
    'form' => $form,
    'model' => $model,
    //'layout' => '<div class=\"col-xs-12\">{regolaPubblicazione}</div><div class=\"col-xs-12\">{destinatari}</div>',
    'regolaPubblicazione' => [
        'data' => \open20\amos\cwh\models\CwhPubblicazioni::find()->asArray()->all()
    ],
    'renderCols' => true,
    'moduleCwh' => (isset($moduleCwh) ? $moduleCwh : null)
]);
