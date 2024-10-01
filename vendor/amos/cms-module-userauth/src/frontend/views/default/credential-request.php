<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @licence GPLv3
 * @licence https://opensource.org/licenses/gpl-3.0.html GNU General Public Proscription version 3
 *
 * @package amos-admin
 * @category CategoryName
 */

use amos\userauth\frontend\Module;
use open20\amos\admin\AmosAdmin;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var $socialAuthModule \open20\amos\socialauth\Module
 * @var yii\web\View $this
 * @var yii\bootstrap\ActiveForm $form
 * @var amos\userauth\models\CredentialRequestForm $model
 */

$referrer = \Yii::$app->request->referrer;
if ((strpos($referrer, 'javascript') !== false) || (strpos($referrer, \Yii::$app->params['backendUrl']) == false)) {
    $referrer = null;
}

$text = AmosAdmin::t('amosadmin', "#register_privacy_alert_not_accepted");

?>
<div class="container py-4 my-5 credential-request">
    <div class="row nop">
        <div class="col-md-6 mx-auto">
            <h2 class="h5"><?= Module::t('Richiedi le credenziali di accesso') ?></h2>
            <p class="mb-0"><?= Module::t("Per richiedere le credenziali di accesso devi compilare 
                tutti i campi del modulo e cliccare sul pulsante in basso INVIA: 
                la tua domanda sarà verificata e, se approvata, riceverai un invito all'indirizzo e-mail da te 
                compilato nel campo sottostante, per registrarti ed accedere all'area riservata.") ?></p>
            <div class="form-rounded">

                <?php
                $form = ActiveForm::begin(
                    [
                        'options' =>
                            [
                                'class' => 'userauth-credential-request-form needs-validation form-rounded'
                            ]
                    ]
                )
                ?>

                <?= $form->field($model, 'nome')->textInput(['maxlength' => true]); ?>

                <?= $form->field($model, 'cognome')->textInput(['maxlength' => true]); ?>

                <?= $form->field($model, 'motivazione')->textInput(['maxlength' => true]); ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => true]); ?>

                <div class="col-xs-12"><?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className())->label('') ?></div>
                <div class="col-xs-12 text-bottom">
                    <div>

                    <?=
                        Html::a(AmosAdmin::t('amosadmin', '#cookie_policy_message'), '/site/privacy',
                            ['title' => AmosAdmin::t('amosadmin', '#cookie_policy_title'), 'target' => '_blank'])
                    ?>
                    <?= Html::tag('p', AmosAdmin::t('amosadmin', '#cookie_policy_content')) ?>
                    <?= $form->field($model, 'privacy')->radioList([
                        1 => AmosAdmin::t('amosadmin', '#cookie_policy_ok'),
                        0 => AmosAdmin::t('amosadmin', '#cookie_policy_not_ok')
                    ]); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <?= Html::a(Module::t('Annulla'),
                    (strip_tags($referrer) ?: ['/login']),
                    ['class' => 'btn btn-outline-primary pull-left',
                    'title' => Module::t('#go_to_login_title')])
                ?>
                <?= Html::submitButton(Module::t('#reset_pwd_send'),
                        ['class' => 'btn btn-primary btn-administration-primary pull-right',
                        'name' => 'login-button',
                        'title' => Module::t('#forgot_pwd_send_title')])
                ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>



