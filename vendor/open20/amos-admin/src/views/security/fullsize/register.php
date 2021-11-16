<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\security
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use yii\helpers\ArrayHelper;

ModuleAdminAsset::register(Yii::$app->view);

/**
 * @var yii\web\View $this
 * @var yii\bootstrap\ActiveForm $form
 * @var \open20\amos\admin\models\RegisterForm $model
 */
$text = AmosAdmin::t('amosadmin', "#register_privacy_alert_not_accepted");

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


JS;

$this->registerJs($js);

$this->title = AmosAdmin::t('amosadmin', 'Login');
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var $socialAuthModule \open20\amos\socialauth\Module
 */
$socialAuthModule = Yii::$app->getModule('socialauth');

$socialMatch = Yii::$app->session->get('social-pending');
$socialProfile = Yii::$app->session->get('social-profile');
$communityId = \Yii::$app->request->get('community');
$redirectUrl = \Yii::$app->request->get('redirectUrl');
?>

<div id="bk-formDefaultLogin" class="loginContainerFullsize">

    <?php if (!isset(Yii::$app->params['logo']) || !Yii::$app->params['logo']) : ?>
        <p class="welcome-message"><?= AmosAdmin::t('amosadmin', '#login_welcome_message') ?></p>
    <?php endif; ?>

    <?php if ($socialAuthModule && $socialAuthModule->enableLogin && !$socialMatch) : ?>
        <div class="social-block social-register-block col-xs-12 nop">
            <?= $this->render('parts' . DIRECTORY_SEPARATOR . 'social', [
                'type' => 'register',
                'communityId' => $communityId,
                'redirectUrl' => $redirectUrl
            ]); ?>
        </div>
    <?php endif; ?>

    <?php if ($socialProfile) :
        echo Html::tag('div',
            Html::tag('p',
                AmosAdmin::t('amosadmin', 'You are right to register using {provider} account', ['provider' => $socialMatch]), ['class' => '']
            ),
            ['class' => 'social-block social-register-block col-xs-12 nop']
        );
    endif;
    ?>

    <div class="col-xs-12 nop login-block registration-block">
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <div class="login-body">
            <?= Html::tag('h2', AmosAdmin::t('amosadmin', '#fullsize_register'), ['class' => 'title-login']) ?>
            <div class="row">
                <div class="col-xs-12 nop">
                    <div class="col-xs-12">
                        <?= $form->field($model, 'nome')->textInput(['placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_name')])->label('') ?>
                        <?= AmosIcons::show('user', '', AmosIcons::IC) ?>
                    </div>
                    <div class="col-xs-12">
                        <?= $form->field($model, 'cognome')->textInput(['placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_surname')])->label('') ?>
                        <?= AmosIcons::show('user', '', AmosIcons::IC) ?>
                    </div>
                    <div class="col-xs-12">
                        <?= $form->field($model, 'email')->textInput(['placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_email')])->label('') ?>
                        <?= AmosIcons::show('mail', '', AmosIcons::IC) ?>
                    </div>

                    <div class="col-xs-12 cookie-privacy">
                        <?= Html::a(AmosAdmin::t('amosadmin', '#cookie_policy_message'), '/site/privacy', ['title' => AmosAdmin::t('amosadmin', '#cookie_policy_title'), 'target' => '_blank']) ?>
                        <?= Html::tag('p', AmosAdmin::t('amosadmin', '#cookie_policy_content')) ?>
                        <?= $form->field($model, 'privacy')->radioList([
                            1 => AmosAdmin::t('amosadmin', '#cookie_policy_ok'),
                            0 => AmosAdmin::t('amosadmin', '#cookie_policy_not_ok')
                        ]); ?>
                    </div>
                    <div class="col-xs-12 recaptcha"><?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className())->label('') ?></div>

                    <?php
                    if ($communityId) { ?>
                        <?= Html::hiddenInput('community', $communityId) ?>
                    <?php }else if($redirectUrl){ ?>
                        <?= Html::hiddenInput('redirectUrl', $redirectUrl) ?>
                    <?php } ?>

                    <?php
                    if ($iuid) { ?>
                        <?= Html::hiddenInput('iuid', $iuid)?>
                    <?php } 
                    ?>
                    
                </div>
            </div>
        </div>
        <div class="col-xs-12 action-block">
            <?= Html::submitButton(AmosAdmin::t('amosadmin', '#text_button_register'), ['class' => 'btn btn-secondary', 'name' => 'login-button', 'title' => AmosAdmin::t('amosadmin', '#text_button_register')]) ?>
            <?php ActiveForm::end(); ?>
            <?= Html::a(AmosAdmin::t('amosadmin', '#go_to_login'), ['/'.AmosAdmin::getModuleName().'/security/login'], ['class' => 'btn btn-navigation-primary', 'title' => AmosAdmin::t('amosadmin', '#go_to_login_title'), 'target' => '_self']) ?>
        </div>
    </div>

    <div class="col-xs-12 reactivate-profile-block">
        <?= Html::a(AmosAdmin::t('amosadmin', '#reactive_profile'), ['/'.AmosAdmin::getModuleName().'/security/reactivate-profile'], ['class' => '', 'title' => AmosAdmin::t('amosadmin', '#reactive_profile'), 'target' => '_self']) ?>
    </div>

    <?php
    \yii\bootstrap\Modal::begin(['id' => 'modal-privacy']);

    echo Html::tag('div',

        Html::a(AmosAdmin::t('amosadmin', '#cookie_policy_message'), '/site/privacy', ['title' => AmosAdmin::t('amosadmin', '#cookie_policy_title'), 'target' => '_blank']) .
        Html::tag('p', AmosAdmin::t('amosadmin', '#cookie_policy_content')) .
        Html::radioList('privacy', null, [AmosAdmin::t('amosadmin', '#cookie_policy_ok'), AmosAdmin::t('amosadmin', '#cookie_policy_not_ok')], ['class' => 'radio radio-privacy'])

        , ['class' => 'text-bottom']);

    echo Html::tag('div',

        Html::submitButton(AmosAdmin::t('amosadmin', '#register_now'), ['class' => 'btn btn-primary btn-administration-primary pull-right', 'id' => 'confirm-privacy-button', 'title' => AmosAdmin::t('amosadmin', '#register_now')]) .
        Html::a(AmosAdmin::t('amosadmin', '#go_to_login'), null, ['data-dismiss' => 'modal', 'class' => 'btn btn-secondary pull-left', 'title' => AmosAdmin::t('amosadmin', '#go_to_login_title'), 'target' => '_self'])

    );

    \yii\bootstrap\Modal::end();
    ?>


</div>
