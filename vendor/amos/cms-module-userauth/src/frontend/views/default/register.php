<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\security
 * @category   CategoryName
 */

use amos\userauth\frontend\Module;
use himiklab\yii2\recaptcha\ReCaptcha;
use luya\helpers\Html;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\components\bootstrapitalia\ActiveForm;
use yii\web\View;

$theModule = Module::instance();

$currentAsset = BootstrapItaliaDesignAsset::register($this);

/**
 * @var View $this
 * @var ActiveForm $form
 * @var open20\amos\admin\models\RegisterForm $model
 */

$text = Module::t("#register_privacy_alert_not_accepted");

$privacyElementId = Html::getInputId($model, 'privacy');

$js = <<<JS
    var selected_social_url = '';
    $('.social-link').click(function(event){
        selected_social_url = $(this).attr('href');
        event.preventDefault();
        $('#modal-privacy').modal('show');
    });

    $('.radio-privacy input').click(function(){
         var checked = $('.radio-privacy input:checked').val();
         if(checked == 1){
         $('.radio').append('<p class="help-block help-block-error">'+'$text'+'</p>');
         }
         else {
           $('.radio p').remove();
        }
    });

    $('#confirm-privacy-button').click(function(){
        var checked = $('.radio-privacy input:checked').val();
       if(checked == 0) {
            window.open(selected_social_url);
            $('#modal-privacy').modal('toggle');
        }
    });

    var privacyElement = $('#$privacyElementId');

    function checkPrivacy(elem) {
        if (elem.is(":checked")) {
            elem.val(1);
        } else {
            elem.val(0);
        }
    }

    checkPrivacy(privacyElement);

    privacyElement.on('change', function(event) {
        checkPrivacy($(this));
    })


JS;

$this->registerJs($js);

$this->title = Module::t('Login');
//$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $socialAuthModule \open20\amos\socialauth\Module
 */

$socialAuthModule = Yii::$app->getModule('socialauth');
$socialMatch = Yii::$app->session->get('social-pending');
$socialProfile = Yii::$app->session->get('social-profile');
$communityId = Yii::$app->request->get('community');
$redirectUrl = Yii::$app->request->get('redirectUrl');
$privacyLink = \Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'];
$customPrivacyCheck = \Yii::$app->params['layoutConfigurations']['customPlatformPrivacyCheck'];

$spidData = \Yii::$app->session->get('IDM');
if (!empty($spidData)) {
    if (!empty($spidData['nome'])) {
        $nomeReadonly = true;
    }
    if (!empty($spidData['cognome'])) {
        $cognomeReadonly = true;
    }
    if (!empty($spidData['codiceFiscale'])) {
        $codiceFiscale = $spidData['codiceFiscale'];
    }
}

?>

<div class="container py-5">

    <div class="register-page row">
        <div class="<?= ($theModule->enableSPID || $theModule->enableSocial) ? 'col-md-6 pr-md-4' : 'col-12' ?>">
            <?php $form = ActiveForm::begin([
                'id' => 'register-form',
                'options' => [
                    'class' => 'userauth-login-form needs-validation form-rounded'
                ]
            ]);
            ?>
            <div class="register-body">
                <h2><?= Module::t('Registrati inserendo i tuoi dati') ?></h2>
                <div class="pt-5">
                    <div class="form-container">
    
                        <?= $form->field($model, 'nome')->textInput(['readonly' => (!empty($model->nome))]); ?>
    
                        <?= $form->field($model, 'cognome')->textInput(['readonly' => (!empty($model->cognome))]); ?>

                        <?= $form->field($model, 'email')->textInput(); ?>

                        <?php if (!empty($spidData)) {
                            echo Html::hiddenInput('reg_with_spid', 1);
                        } ?>


                        <?= \open20\amos\core\helpers\Html::hiddenInput(\open20\amos\core\helpers\Html::getInputName($model, 'moduleName'), $model->moduleName, ['id' => \open20\amos\core\helpers\Html::getInputId($model, 'moduleName')]) ?>
                        <?= \open20\amos\core\helpers\Html::hiddenInput(\open20\amos\core\helpers\Html::getInputName($model, 'contextModelId'), $model->contextModelId, ['id' => \open20\amos\core\helpers\Html::getInputId($model, 'contextModelId')]) ?>

                        <?php if ($customPrivacyCheck) : ?>
                            <?php
                            echo $this->render($customPrivacyCheck, [
                                'currentAsset' => $currentAsset,
                                'form' => $form,
                                'model' => $model,
                                'privacyLink' => $privacyLink,
                                'privacyAttribute' => 'privacy'
                            ]);
                            ?>
                        <?php else : ?>
                            <div class="privacy-policy">
                                <?= Html::a(
                                    Module::t('#cookie_policy_message') .
                                        '<svg class="icon icon-xs ml-2"><use xlink:href="' . $currentAsset->baseUrl . '/sprite/material-sprite.svg#open-in-new"></use></svg>',
                                    $privacyLink,
                                    [
                                        'title' => Module::t('#cookie_policy_title'),
                                        'target' => '_blank'
                                    ]
                                )
                                ?>
                                <?= Html::tag('p', Module::t('#cookie_policy_content')) ?>
                                <?= $form->field($model, 'privacy')->checkbox(['value' => 0, 'label' => Module::t('#cookie_policy_ok')]); ?>
                            </div>
                        <?php endif ?>

                        <div class="recaptcha">
                            <?= $form->field($model, 'reCaptcha')->widget(ReCaptcha::className())->label('') ?>
                        </div>

                        <?php
                        if ($communityId) { ?>
                            <?= Html::hiddenInput('community', $communityId) ?>
                        <?php } else if ($redirectUrl) { ?>
                            <?= Html::hiddenInput('redirectUrl', $redirectUrl) ?>
                        <?php } ?>

                        <?php
                        if ($iuid) { ?>
                            <?= Html::hiddenInput('iuid', $iuid) ?>
                        <?php }

                        ?>
                        <?php if (!empty($spidData)) { ?>
                            <?= Html::hiddenInput('reg_with_spid', 1) ?>
                        <?php } ?>


                    </div>
                </div>
                <div class="d-flex flex-row-reverse justify-content-end">
                    <?= Html::submitButton(Module::t('#text_button_register'), ['class' => 'btn btn-primary mx-2', 'name' => 'register-button', 'title' => Module::t('#text_button_register')]) ?>
                    <?php ActiveForm::end(); ?>
                    <?= Html::a(Module::t('Annulla'), [$loginUrl], ['class' => 'btn btn-outline-primary', 'title' => Module::t('#go_to_login_title'), 'target' => '_self']) ?>
                </div>
            </div>
        </div>
        <div class="<?= ($theModule->enableSPID || $theModule->enableSocial || $theModule->enableAgidLogin || $theModule->enablePuaLogin) ? 'col-md-5 offset-md-1 pl-md-4' : 'col-12' ?>">
            <?php
            if ($theModule->enableSPID) {
                ?>
                <div class="mt-5">
                    <div class="social-block social-register-block mb-5">
                        <?= $this->render(
                            'parts' . DIRECTORY_SEPARATOR . 'bi-idpc',
                            [
                                'currentAsset' => $currentAsset,
                                'hideIdpcButtonInfo' => $hideIdpcButtonInfo,
                                'hideIdpcButtonTitle' => $hideIdpcButtonTitle,
                                'hideIdpcButtonSubtitle' => $hideIdpcButtonSubtitle
                            ]
                        ); ?>
                    </div>
                </div>
            <?php
            }
            ?>
            <?php
            if ($theModule->enableAgidLogin) {
            ?>
                <div class="mt-5">
                    <div class="social-block social-register-block mb-5">
                        <?= $this->render(
                            'parts' . DIRECTORY_SEPARATOR . 'bi-idpc',
                            [
                                'currentAsset' => $currentAsset,
                                'hideIdpcButtonInfo' => $hideIdpcAgidButtonInfo,
                                'hideIdpcButtonTitle' => $hideIdpcAgidButtonTitle,
                                'hideIdpcButtonSubtitle' => $hideIdpcAgidButtonSubtitle
                            ]
                        ); ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php
            if ($theModule->enablePuaLogin) {
            ?>
                <div class="mt-5">
                    <div class="social-block social-register-block mb-5">
                        <?= $this->render(
                            'parts' . DIRECTORY_SEPARATOR . 'bi-pua',
                            [
                                'currentAsset' => $currentAsset,
                                'hideIdpcButtonInfo' => $hideIdpcAgidButtonInfo,
                                'hideIdpcButtonTitle' => $hideIdpcAgidButtonTitle,
                                'hideIdpcButtonSubtitle' => $hideIdpcAgidButtonSubtitle
                            ]
                        ); ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php
            if ($theModule->enableSocial) {
                ?>
                <div class="mt-5">
                    <?php if ($socialAuthModule && $socialAuthModule->enableLogin && !$socialMatch) : ?>
                        <div class="social-block social-register-block mb-5">
                            <?= $this->render('parts' . DIRECTORY_SEPARATOR . 'bi-social', [
                                'type' => 'register',
                                'communityId' => $communityId,
                                'redirectUrl' => $redirectUrl
                            ]); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($socialProfile) :
                        echo Html::tag(
                            'div',
                            Html::tag(
                                'p',
                                Module::t('You are right to register using {provider} account', ['provider' => $socialMatch]),
                                ['class' => '']
                            ),
                            ['class' => 'social-block social-register-block col-xs-12 nop']
                        );
                    endif;
                    ?>
                </div>
            <?php
            }
            ?>
        </div>

    </div>


    <div class="modal" id="modal-privacy" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Privacy Policy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    echo Html::tag(
                        'div',
                        Html::a(Module::t('#cookie_policy_message'), '/site/privacy', ['title' => Module::t('#cookie_policy_title'), 'target' => '_blank']) .
                            Html::tag('p', Module::t('#cookie_policy_content')) .
                            Html::radioList('privacy', null, [Module::t('#cookie_policy_ok'), Module::t('#cookie_policy_not_ok')], ['class' => 'radio radio-privacy']),
                        ['class' => 'text-bottom']
                    );

                    echo Html::tag(
                        'div',
                        Html::submitButton(Module::t('#register_now'), ['class' => 'btn btn-primary btn-administration-primary pull-right', 'id' => 'confirm-privacy-button', 'title' => Module::t('#register_now')]) .
                            Html::a(Module::t('#go_to_login'), null, ['data-dismiss' => 'modal', 'class' => 'btn btn-outline-primary pull-left', 'title' => Module::t('#go_to_login_title'), 'target' => '_self'])
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>