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
use open20\amos\core\helpers\Html;
use open20\amos\invitations\models\InvitationUser;
use open20\amos\invitations\Module;

/**
 * @var yii\web\View $this
 * @var $model \open20\amos\invitations\models\Invitation
 */

$moduleName = null;
$contextModelId = null;

$moduleName = $model->module_name;
$contextModelId = $model->context_model_id;
?>

<?php $form = ActiveForm::begin() ?>
<div class="col-lg-12">
    <?php echo "<strong>" . Module::t('amosinvitations', 'Name and surname:') . '</strong> ' . $model->nameSurname ?>
</div>
<div class="col-lg-12">
    <?php echo "<strong>" . Module::t('amosinvitations', 'Invitations sent:') . '</strong> ' . $model->invitationUser->numberNotificationSended; ?>
</div>
<div class="col-lg-12">
    <?php
    /** @var InvitationUser $invitationUserModel */
    $invitationUserModel = $this->invitationsModule->createModel('InvitationUser');
    $invitationUser = $invitationUserModel::getInvitationUserFromEmail($model->invitationUser->email);
    $invitation = $invitationUser->getInvitations()->orderBy('send_time DESC')->one()
    ?>
    <?php echo "<strong>" . Module::t('amosinvitations', 'Last invitation sent at:') . '</strong> ' . \Yii::$app->formatter->asDatetime($invitation->send_time) ?>
</div>
<div>
    <?= Html::a(Module::t('amosinvitations', 'Re-send'), [
        '/invitations/invitation/re-send',
        'id' => $model->id,
        'moduleName' => $moduleName,
        'contextModelId' => $contextModelId
    ], ['class' => 'btn btn-primary pull-right']) ?>
    <?php CloseSaveButtonWidget::widget(['model' => $model, 'buttonId' => 're-send-button', 'buttonSaveLabel' => Module::t('amosinvitations', 'Send')]); ?>
</div>
<?php ActiveForm::end(); ?>
