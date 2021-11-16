<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\showcaseprojects\views\showcase-project-proposal
 * @category   CategoryName
 */

use open20\amos\invitations\Module;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use yii\bootstrap\Modal;

/**
 * @var \open20\amos\invitations\models\Invitation $invitation
 * @var \open20\amos\invitations\models\InvitationUser $invitationUser
 */

Modal::begin([
    'header' => Module::t('amosinvitations', '#invite_user_modal_title'),
    'id' => 'invite-new-user-modal',
    'size' => Modal::SIZE_LARGE
]);

$url = '/invitations/invitation/invite-user';
$formErrorMessage = Module::t('amosinvitations', '#invitation_form_error');

$js = <<<JS

$("#invite-form").submit(function(e) { 
    if ($(this).data('submitted') === true) {
      // Previously submitted - don't submit again
      e.preventDefault();
      return false;
    } else {
      // Mark it so that the next submit can be ignored
      $(this).data('submitted', true);
    }
    var postdata = $(this).serializeArray();
    var formurl = $(this).attr("action");
    $.ajax( {
        url : formurl,
        type: "POST", 
        data : postdata, 
        success:function(data, textStatus, jqXHR) { //data: returning of data from the server
            $('#invite-new-user-modal').modal('hide');
            $('#response-text').text(data);
            $('#invite-new-user-response-modal').modal('show');
            $('#invitation-name').val('');
           $('#invitation-surname').val('');
           // $('#invitation-message').val('');
           $('#email').val('');
          if(!$('#form-errors').hasClass('hidden')){
            $('#form-errors').addClass('hidden');
         }
          if(!$('#check-email').hasClass('hidden')){
            $('#check-email').addClass('hidden');
         }
         // $(this).removeProp('data-submitted');
        }, 
        error: function(jqXHR, textStatus, errorThrown) { 
            console.log(errorThrown); 
        }
    });
    e.preventDefault(); // default action us stopped here 
    return false;
}); 

$('#send-invitation').on('click', function(e) {
    e.preventDefault();
    var name = ($('#invitation-name').val().length);
    var surname = ($('#invitation-surname').val().length);
    var message = ($('#invitation-message').val().length);
    var email = ($('#email').val().length);
    var emailWrong = $('.field-email').hasClass('has-error') || !$('#already-present').hasClass('hidden');
    var ok = name && surname && message && email && !emailWrong;
    var errors = $('#form-errors');
     if(ok){
         if(!errors.hasClass('hidden')){
            errors.addClass('hidden');
         }
         return true;
     }else {
          errors.removeClass('hidden');
           return false;
     }
});

function checkEmail(email){
  $.get("/invitations/invitation/check-email-ajax", { email: email } )
    .done(function( data ) { 
        data = $.parseJSON(data);
        if(data.success) {
            $('#check-email').text(data.message);
             if(!$('#already-present').hasClass('hidden')){
               $('#already-present').addClass('hidden');
            }
            if (data.message == '') {
                if(!$('#check-email').hasClass('hidden')){
                    $('#check-email').addClass('hidden');
                }
            } else {       
                if($('#check-email').hasClass('hidden')){
                    $('#check-email').removeClass('hidden');
                }
            }
            // alert(data.messageConfirm);
            $('#send-invitation').data('confirm', data.messageConfirm);
        }else{
            $('#already-present').text(data.message);
            if($('#already-present').hasClass('hidden')){
               $('#already-present').removeClass('hidden');
            }
            if(!$('#check-email').hasClass('hidden')){
                $('#check-email').addClass('hidden');
            }
        }
    });
}
checkEmail($('#email').val());

$('#email').change(function(e) {
    e.preventDefault();
    checkEmail( $(this).val());
    return true;
});

$( "#invitation-form" ).submit(function( event ) {
  $('#my-modal').modal('hide');
});
    
JS;
$this->registerJs($js, \yii\web\View::POS_READY);

?>

    <div id="insert-user-container">

        <?php $formInvitation = ActiveForm::begin(['id' => 'invite-form', 'action' => $url]); ?>
        <div class="col-lg-12 col-sm-12 alert alert-danger hidden " id="form-errors"><?= $formErrorMessage ?></div>
        <div class="row">
            <div class="col-lg-6 col-sm-6">
                <?= $formInvitation->field($invitation, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-6 col-sm-6">
                <?= $formInvitation->field($invitation, 'surname')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $formInvitation->field($invitationUser, 'email')->textInput(['id' => 'email']) ?>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 alert alert-warning hidden " id="check-email"></div>
        <div class="col-lg-12 col-sm-12 alert alert-danger hidden " id="already-present"></div>

        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $formInvitation->field($invitation, 'message')->widget(\yii\redactor\widgets\Redactor::className(), [
                    'clientOptions' => [
                        'buttonsHide' => [
                            'image',
                            'file'
                        ],
                        'lang' => substr(Yii::$app->language, 0, 2)
                    ]
                ]) ?>
            </div>
        </div>

        <div class='bk-btnFormContainer'>
            <?= Html::submitButton(Module::t('amosinvitations', 'Send invitation'), [
                'class' => 'btn btn-primary',
                'id' => 'send-invitation',
//                'data-confirm' => Module::t('amosinvitations', 'Confirm send invitation')
//                'data-toggle' => 'modal',
//                'data-target' => '#my-modal'
            ]) ?>
            <?= Html::a(Module::t('amoscore', 'Annulla'), null,
                ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

<?php
Modal::end();

Modal::begin([
    'header' => Module::t('amosinvitations', '#invite_user_modal_title'),
    'id' => 'invite-new-user-response-modal',
]);
?>
    <div id="response-text" class="m-b-30">
        <!-- filled by javascript -->
    </div>
<?php
Modal::end();
?>
