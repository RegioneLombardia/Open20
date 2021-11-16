<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\views\invitation
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\invitations\Module;

/**
 * @var yii\web\View $this
 * @var open20\amos\invitations\models\Invitation $invitation
 * @var open20\amos\invitations\models\InvitationUser $invitationUser
 * @var yii\widgets\ActiveForm $form
 */

/** @var Module $invitationsModule */
$invitationsModule = Module::instance();

$allowOneInvitePerMail = $invitationsModule->allowOneInvitePerMail;
$email = $invitationUser->email;
$errorAjax = Module::t('amosinvitations', '#form_ajax_error');

$js = <<<JS

function checkEmail(email) {
$.get("/invitations/invitation/check-email-ajax", {email: email})
    .done(function(data) {
        data = $.parseJSON(data);
        var checkMailElement = $('#check-email');
        if (data.success !== undefined) {
            checkMailElement.html(data.message);
            if (data.message == '') {
                checkMailElement.hide();
            } else {       
                checkMailElement.show();
            }
            $('#info-content').html(data.messageConfirm);
            if ((data.success === 0) || data.oneInvitePerMail) {
                $('#btn-send-invitation-modal-id').hide();
            } else {
                $('#btn-send-invitation-modal-id').show();
            }
        } else {
            checkMailElement.html('');
            checkMailElement.show();
        }
    });
}

checkEmail('$email');

$("#send-invitation").prop("type", "button");

$('#email').change(function(e) {
    e.preventDefault();
    checkEmail($(this).val());
    return true;
});

$("#my-form").submit(function(event) {
    $('#my-modal').modal('hide');
});

JS;
$this->registerJs($js);
?>


<div class="invitation-form col-xs-12 nop">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'my-form'
        ]
    ]); ?>
    
    <?php $this->beginBlock('default'); ?>

    <div class="col-lg-6 col-sm-6">
        <?= $form->field($invitation, 'name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-6 col-sm-6">
        <?= $form->field($invitation, 'surname')->textInput(['maxlength' => true]) ?>
    </div>
    
    <?php if ($invitationsModule->enableFiscalCode): ?>
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($invitationUser, 'email')->textInput(['id' => 'email']) ?>
        </div>
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($invitation, 'fiscal_code')->textInput(['maxlength' => true]) ?>
        </div>
    <?php else: ?>
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($invitationUser, 'email')->textInput(['id' => 'email']) ?>
        </div>
    <?php endif; ?>

    <div class="col-lg-12 col-sm-12 alert alert-warning" style="display: none;" id="check-email"></div>
    
    <?php if ($invitationsModule->enableInviteMessage): ?>
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($invitation, 'message')->widget(TextEditorWidget::className(), [
                'clientOptions' => [
                    'placeholder' => Module::t('amosinvitations', '#message_field_placeholder'),
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]) ?>
        </div>
    <?php endif; ?>

    <div class="clearfix"></div>
    
    <?php $this->endBlock(); ?>
    
    <?php
    $itemsTab[] = [
        'label' => Module::t('amosinvitations', 'Default'),
        'content' => $this->blocks['default'],
    ];
    ?>
    
    <?= Tabs::widget([
        'encodeLabels' => false,
        'items' => $itemsTab
    ]); ?>

    <!-- Modal -->
    <div class="modal fade" id="my-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?= Module::t('amosinvitations', 'Confirm send invitation') ?></h4>
                </div>
                <div class="modal-body" id="info-content"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary undo-edit" data-dismiss="modal"><?= Yii::t('amoscore', 'Annulla') ?></button>
                    <button type="submit" class="btn btn-primary" id="btn-send-invitation-modal-id"><?= Module::t('amosinvitations', 'Send invitation') ?></button>
                </div>
            </div>
        </div>
    </div>
    
    <?= CloseSaveButtonWidget::widget([
        'model' => $invitation,
        'buttonNewSaveLabel' => Module::t('amosinvitations', '#form_save_btn_label'),
        'buttonSaveLabel' => Module::t('amosinvitations', '#form_save_btn_label'),
        'dataToggle' => 'modal',
        'dataTarget' => '#my-modal',
        'buttonId' => 'send-invitation',
    ]); ?>
    <?php ActiveForm::end(); ?>
</div>
