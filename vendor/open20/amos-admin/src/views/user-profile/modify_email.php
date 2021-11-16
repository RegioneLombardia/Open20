<?php
use open20\amos\admin\AmosAdmin;
/**@var $user \open20\amos\admin\models\UserProfile
 * @var $model \open20\amos\admin\models\UserOtpCode
 */

$this->title = \open20\amos\admin\AmosAdmin::t('amosadmin','Modifica ed autentica email');
$this->params['breadcrumbs'][] = ['label' => 'modifica profilo', 'url' => ['update', 'id' => $user->userProfile->id]];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="form-auth-email">
    <?php $form = \open20\amos\core\forms\ActiveForm::begin() ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <?= $form->field($user, 'email')->textInput(['readonly' => $inserisciCodice ]);?>
        </div>
        <div class="col-xs-12 col-sm-6 m-t-30">
            <?php $label = AmosAdmin::t('amosadmin', 'Confirm email');
            if($inserisciCodice){
                $label = AmosAdmin::t('amosadmin', 'Invia dinuovo il codice');
            }?>
            <?php echo \open20\amos\core\helpers\Html::submitButton($label, [
                    'class' => 'btn btn-primary',
                'id' => 'save-code',
                'name' => 'save-code',
                'value' => 1
            ]); ?>
        </div>
    </div>
    <?php if($inserisciCodice) {?>
    <div class="row m-t-25">
        <div class="col-xs-12 col-sm-6 nop">
            <div class="col-xs-12">
                <h4 class="bold"><?= AmosAdmin::t('amosadmin', 'Inserire il codice che ti è stato inviato via email nel campo qui sotto.')?></h4>
            </div>
            <div class="col-sm-8 col-xs-12">
                <?= $form->field($model, 'auth_code')->label(AmosAdmin::t('amosadmin','OTP Code'));?>
            </div>
            <div class="col-sm-4 col-xs-12 m-t-25 text-right">
                <?php echo \open20\amos\core\helpers\Html::submitButton(AmosAdmin::t('amosadmin', 'Confirm OTP code'),['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    </div>
    <?php } else { ?>
        <div class="clearfix"></div>
        <div class="pull-right m-t-25">
        </div>
    <?php }?>
    <?php \open20\amos\core\forms\ActiveForm::end();?>
</div>
