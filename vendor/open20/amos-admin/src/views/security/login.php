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
use yii\helpers\Url;

ModuleAdminAsset::register(Yii::$app->view);

/**
 * @var yii\web\View $this
 * @var yii\bootstrap\ActiveForm $form
 * @var \open20\amos\admin\models\LoginForm $model
 */
$this->title = AmosAdmin::t('amosadmin', 'Login');
$this->params['breadcrumbs'][] = $this->title;

/** @var $socialAuthModule \open20\amos\socialauth\Module */
$socialAuthModule = \open20\amos\socialauth\Module::getInstance();

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());

$socialMatch = Yii::$app->session->get('social-match');
$socialProfile = Yii::$app->session->get('social-profile');

// for redirect to community after login or registration
$communityId = null;
$previousUrl = Yii::$app->getUser()->getReturnUrl();
$found = strpos($previousUrl, 'community/join?id=');
if ($found) {
    $parsedUrl = parse_url($previousUrl);
    parse_str($parsedUrl['query'], $query_params);
    if ($query_params) {
        $communityId = \open20\amos\admin\utility\UserProfileUtility::cleanIntegerParam($query_params['id']);
    }
}

$isDemoLogin = (isset(\Yii::$app->params['isDemoLogin']) && \Yii::$app->params['isDemoLogin']);
$usernameOrEmailFieldOptions = [];
$usernameFieldOptions = [];
$usernameOrEmailInputOptions = [];
$usernameInputOptions = [];
$passwordFieldOptions = [];
$passwordInputOptions = ['autocomplete' => 'off'];

if ($isDemoLogin) {
    $usernameOrEmailFieldOptions = ['inputOptions' => ['value' => 'demo']];
    $usernameOrEmailInputOptions = ['readonly' => true];
    $usernameFieldOptions = ['inputOptions' => ['value' => 'demo']];
    $usernameInputOptions = ['readonly' => true];
    $passwordFieldOptions = ['inputOptions' => ['value' => 'Demo1234']];
    $passwordInputOptions = ['readonly' => true];
}

Yii::$app->trigger('BEFORE_LOGIN_FORM');
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer loginContainer">
    <div class="header col-xs-12">
        <?php if (!isset(Yii::$app->params['logo']) || !Yii::$app->params['logo']) : ?>
            <p class="welcome-message"><?= AmosAdmin::t('amosadmin', '#login_welcome_message') ?></p>
        <?php endif; ?>

        <?php
        if ($socialProfile) :
            echo Html::tag('h2',
                AmosAdmin::t('amosadmin', 'You are right to link you {provider} account to your profile',
                    [
                        'provider' => $socialMatch
                    ]), ['class' => 'title-login col-xs-12 nop']);
        endif;
        ?>

        <?php if ($socialAuthModule && $socialAuthModule->enableSpid) : ?>
            <?=
            Html::a(
                AmosIcons::show('account-circle') . AmosAdmin::t('amosadmin',  $socialAuthModule->shibbolethConfig['buttonLabel']),
                Url::to("/{$socialAuthModule->id}/shibboleth/endpoint", 'https'),
                [
                    'class' => 'btn btn-spid',
                    'title' => AmosAdmin::t('amosadmin',  $socialAuthModule->shibbolethConfig['buttonLabel']),
                    //'target' => '_blank'
                ]
            )
            ?>
        <?php endif; ?>
    </div>

    <?php if ($socialAuthModule && $socialAuthModule->enableSpid && (CoreCommonUtility::platformSeenFromHeadquarter() || !$adminModule->hideStandardLoginPageSection)) : ?>
        <?= Html::tag('div', Html::tag('span', AmosAdmin::t('amosadmin', '#or')), ['class' => 'or-login col-xs-12 nop']) ?>
    <?php endif; ?>

    <?php if ($socialAuthModule && $socialAuthModule->enableLogin && !$socialMatch) : ?>
        <div class="header col-xs-12">
            <?= $this->render('parts/header', [
                'type' => 'login',
                'communityId' => $communityId
            ]); ?>
        </div>
    <?php endif; ?>

    <?php if ((CoreCommonUtility::platformSeenFromHeadquarter() || !$adminModule->hideStandardLoginPageSection) && $socialAuthModule && $socialAuthModule->enableLogin && !$socialMatch) : ?>
        <?= Html::tag('div', Html::tag('span', AmosAdmin::t('amosadmin', '#or')), ['class' => 'or-login col-xs-12 nop']) ?>
    <?php endif; ?>

    <div class="body col-xs-12 nop">
        <?php if ($adminModule->showLogInRegisterButton || CoreCommonUtility::platformSeenFromHeadquarter() || !$adminModule->hideStandardLoginPageSection): ?>
            <?php if (CoreCommonUtility::platformSeenFromHeadquarter() || !$adminModule->hideStandardLoginPageSection) : ?>
                <?= Html::tag('h2', AmosAdmin::t('amosadmin', '#title_login'), ['class' => 'title-login col-xs-12 nop']) ?>
                <?= Html::tag('h3', AmosAdmin::t('amosadmin', '#subtitle_login'), ['class' => 'subtitle-login col-xs-12 nop']) ?>
            <?php endif; ?>
            <div class="row">
                <?php if (CoreCommonUtility::platformSeenFromHeadquarter() || !$adminModule->hideStandardLoginPageSection) : ?>
                    <div class="col-lg-12 col-sm-12 nop">
                        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                        <?php if (isset(\Yii::$app->params['template-amos']) && \Yii::$app->params['template-amos']): ?>
                            <div class="col-xs-12 m-b-15">
                                <?=
                                $form->field($model, 'ruolo')->dropDownList([
                                    'ADMIN' => AmosAdmin::t('amosadmin', 'Admin'),
                                    'VALIDATED_BASIC_USER' => AmosAdmin::t('amosadmin', 'Validated Basic User')
                                ])
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($adminModule->allowLoginWithEmailOrUsername): ?>
                            <div class="col-xs-12 col-sm-6">
                                <?= $form->field($model, 'usernameOrEmail', $usernameOrEmailFieldOptions)->textInput($usernameOrEmailInputOptions) ?>
                            </div>
                        <?php else: ?>
                            <div class="col-xs-12 col-sm-6">
                                <?= $form->field($model, 'username', $usernameFieldOptions)->textInput($usernameInputOptions) ?>
                            </div>
                        <?php endif; ?>

                        <div class="col-xs-12 col-sm-6">
                            <?= $form->field($model, 'password', $passwordFieldOptions)->passwordInput($passwordInputOptions) ?>
                            <div class="forgot-password">
                                <?= Html::a(AmosAdmin::t('amosadmin', '#forgot_password'), ['/'.AmosAdmin::getModuleName().'/security/forgot-password'],
                                    ['title' => AmosAdmin::t('amosadmin', '#forgot_password_title_link'), 'target' => '_self'])
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <?= $form->field($model, 'rememberMe')->checkbox()->label(AmosAdmin::t('amosadmin', '#remember_access'),
                            ['class' => 'remember-me', 'title' => AmosAdmin::t('amosadmin', '#remember_access')]) ?>
                    </div>

                    <div class="col-xs-12">
                        <?= Html::submitButton(AmosAdmin::t('amosadmin', '#text_button_login'),
                            [
                                'class' => 'btn btn-primary btn-administration-primary pull-right',
                                'name' => 'login-button',
                                'title' => AmosAdmin::t('amosadmin', '#text_button_login_title')
                            ]) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                <?php endif; ?>

                <?php if ($adminModule->enableRegister || (!$adminModule->enableRegister && !empty($adminModule->textWarningForRegisterDisabled))): ?>
                    <div class="col-xs-12 footer-link">
                        <?php
                        $urlRegister = ['/'.AmosAdmin::getModuleName().'/security/register'];
                        if ($communityId) {
                            $urlRegister['community'] = $communityId;
                        }
                        if ($adminModule->showLogInRegisterButton) {
                            echo Html::tag('h3',
                                AmosAdmin::t('amosadmin', '#new_user') .
                                ' ' .
                                Html::a(AmosAdmin::t('amosadmin', '#register_now'), $urlRegister,
                                    [
                                        'class' => '',
                                        'title' => AmosAdmin::t('amosadmin', '#register_now_title_link', ['appName' => Yii::$app->name]),
                                        'target' => '_self'
                                    ]),
                                ['class' => 'subtitle-login nom']
                            );
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
