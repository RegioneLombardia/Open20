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
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\CoreCommonUtility;

ModuleAdminAsset::register(Yii::$app->view);

/**
 * @var yii\web\View $this
 * @var yii\bootstrap\ActiveForm $form
 * @var \open20\amos\admin\models\LoginForm $model
 */
$this->title = AmosAdmin::t('amosadmin', 'Login');
$this->params['breadcrumbs'][] = $this->title;

/** @var $socialAuthModule \open20\amos\socialauth\Module */
$socialAuthModule = Yii::$app->getModule('socialauth');

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());

$socialMatch = Yii::$app->session->get('social-match');
$socialProfile = Yii::$app->session->get('social-profile');

// for redirect to community after login or registration
$communityId = null;
$previousUrl = Yii::$app->getUser()->getReturnUrl();
$enableRedirect = false;

if(strpos($previousUrl, 'enableRedirect')){
    $enableRedirect = true;
}


$found = strpos($previousUrl, 'community/join?id=');
if ($found) {
    $parsedUrl = parse_url($previousUrl);
    parse_str($parsedUrl['query'], $query_params);
    if ($query_params) {
        $communityId = \open20\amos\admin\utility\UserProfileUtility::cleanIntegerParam($query_params['id']);
    }
}

$isDemoLogin = (isset(\Yii::$app->params['isDemoLogin']) && \Yii::$app->params['isDemoLogin']);

$usernameOrEmailFieldOptions = ['labelOptions' => ['class' => 'no-asterisk']];
$usernameOrEmailInputOptions = ['placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_username')];

$usernameFieldOptions = [];
$usernameInputOptions = ['placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_username')];

$passwordFieldOptions = ['labelOptions' => ['class' => 'no-asterisk']];
$passwordInputOptions = ['autocomplete' => 'off', 'placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_password')];

if ($isDemoLogin) {
    $usernameOrEmailFieldOptions = [
        'inputOptions' => ['value' => 'demo'],
        'labelOptions' => ['class' => 'no-asterisk']
    ];
    $usernameOrEmailInputOptions = [
        'readonly' => true,
        'placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_username')
    ];
    $usernameFieldOptions = [
        'inputOptions' => ['value' => 'demo']
    ];
    $usernameInputOptions = [
        'readonly' => true,
        'placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_username')
    ];
    $passwordFieldOptions = [
        'inputOptions' => ['value' => 'Demo1234'],
        'labelOptions' => ['class' => 'no-asterisk']
    ];
    $passwordInputOptions = [
        'readonly' => true,
        'placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_password')
    ];
}

Yii::$app->trigger('BEFORE_LOGIN_FORM');
?>

<div id="bk-formDefaultLogin" class="loginContainerFullsize">
    <div class="login-block col-xs-12 nop">
        <?php if (!isset(Yii::$app->params['logo']) || !Yii::$app->params['logo']) : ?>
            <p class="welcome-message"><?= AmosAdmin::t('amosadmin', '#login_welcome_message') ?></p>
        <?php endif; ?>

        <?php if (CoreCommonUtility::platformSeenFromHeadquarter() || !$adminModule->hideStandardLoginPageSection): ?>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <div class="login-body">
                <?= Html::tag('h2', AmosAdmin::t('amosadmin', '#fullsize_login'), ['class' => 'title-login col-xs-12 nop']) ?>
                <div class="row">
                    <?php if (CoreCommonUtility::platformSeenFromHeadquarter() || !$adminModule->hideStandardLoginPageSection) : ?>
                        <div class="col-xs-12 nop">
                            <?php if (isset(\Yii::$app->params['template-amos']) && \Yii::$app->params['template-amos']): ?>
                                <div class="col-xs-12">
                                    <?=
                                    $form->field($model, 'ruolo')->dropDownList([
                                        'ADMIN' => AmosAdmin::t('amosadmin', 'Admin'),
                                        'VALIDATED_BASIC_USER' => AmosAdmin::t('amosadmin', 'Validated Basic User')
                                    ])
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($adminModule->allowLoginWithEmailOrUsername): ?>
                                <div class="col-xs-12">
                                    <?= $form->field($model, 'usernameOrEmail', $usernameOrEmailFieldOptions)->textInput($usernameOrEmailInputOptions)->label('') ?>
                                    <?= AmosIcons::show('user', '', AmosIcons::IC) ?>
                                </div>
                            <?php else: ?>
                                <div class="col-xs-12">
                                    <?= $form->field($model, 'username', $usernameFieldOptions)->textInput($usernameInputOptions)->label('') ?>
                                    <?= AmosIcons::show('user', '', AmosIcons::IC) ?>
                                </div>
                            <?php endif; ?>

                            <div class="col-xs-12">
                                <?= $form->field($model, 'password', $passwordFieldOptions)->passwordInput($passwordInputOptions)->label('') ?>
                                <?= AmosIcons::show('lucchetto', '', AmosIcons::IC) ?>
                            </div>

                            <div class="col-xs-12 action">
                                <div>
                                    <?= Html::submitButton(AmosAdmin::t('amosadmin', '#text_button_login'),
                                        [
                                            'class' => 'btn btn-secondary',
                                            'name' => 'login-button',
                                            'title' => AmosAdmin::t('amosadmin', '#text_button_login_title')
                                        ]) ?>
                                </div>
                                <div class="forgot-password">
                                    <?= Html::a(AmosAdmin::t('amosadmin', '#forgot_password'), ['/'.AmosAdmin::getModuleName().'/security/forgot-password'],
                                        ['title' => AmosAdmin::t('amosadmin', '#forgot_password_title_link'), 'target' => '_self'])
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-xs-12 nop rememberme">
                <?= $form->field($model, 'rememberMe')->checkbox()->label(AmosAdmin::t('amosadmin', '#remember_access'),
                    ['class' => 'remember-me', 'title' => AmosAdmin::t('amosadmin', '#remember_access')]) ?>
            </div>
            <?php ActiveForm::end(); ?>
        <?php endif; ?>
    </div>

    <?php if ($socialAuthModule && $socialAuthModule->enableLogin && !$socialMatch) : ?>
        <div class="social-block col-xs-12 nop">
            <?= $this->render('parts' . DIRECTORY_SEPARATOR . 'social', [
                'type' => 'login',
                'communityId' => $communityId
            ]); ?>
        </div>
    <?php endif; ?>

    <?php if ($socialProfile) :
        echo Html::tag('div',
            Html::tag('p',
                AmosAdmin::t('amosadmin', 'You are right to link you {provider} account to your profile', ['provider' => $socialMatch]), ['class' => '']
            ),
            ['class' => 'social-block social-register-block col-xs-12 nop']
        );
    endif;
    ?>

    <?php if ($socialAuthModule && $socialAuthModule->enableSpid) : ?>
        <div class="spid-block col-xs-12 nop">
            <?= $this->render('parts' . DIRECTORY_SEPARATOR . 'spid'); ?>
        </div>
    <?php endif; ?>

    <?php if (($adminModule->enableRegister && $adminModule->showLogInRegisterButton) || (!$adminModule->enableRegister && !empty($adminModule->textWarningForRegisterDisabled))): ?>
        <div class="register-block col-xs-12 nop">
            <?php
            $urlRegister = ['/'.AmosAdmin::getModuleName().'/security/register'];
            if ($communityId) {
                $urlRegister['community'] = $communityId;
            } else if ($enableRedirect) {
                $urlRegister['redirectUrl'] = $previousUrl;
            }
            echo Html::tag('h2',
                AmosAdmin::t('amosadmin', '#fullsize_register_now') .
                ' ' .
                Html::a(AmosAdmin::t('amosadmin', '#fullsize_register_now_title_link'), $urlRegister,
                    [
                        'title' => AmosAdmin::t('amosadmin', '#fullsize_register_now_title_link', ['appName' => Yii::$app->name]),
                        'target' => '_self'
                    ]),
                ['class' => 'title-login']
            );
            ?>
        </div>
    <?php endif; ?>
</div>
