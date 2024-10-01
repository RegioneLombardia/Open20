<?php

use amos\userauth\frontend\Module;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\utilities\CoreCommonUtility;
use luya\helpers\Html;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\components\bootstrapitalia\ActiveForm;
use amos\userauth\frontend\utility\CmsUserauthUtility;

$theModule = Module::instance();

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$socialMatch = Yii::$app->session->get('social-match');
$socialProfile = Yii::$app->session->get('social-profile');
$language_code = Yii::$app->composition['langShortCode'];

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());
?>

<?php if (!$showLiteMode) : ?>
    <?php
    $form = ActiveForm::begin(
                    [
                        'options' =>
                        [
                            'class' => 'userauth-login-form needs-validation form-rounded',
                            'autocomplete' => 'off'
                        ]
                    ]
    );
    ?>

    <div class="<?= (CmsUserauthUtility::isAccessPermitted() && $theModule->showLoginStandardUnderVpn) ? 'callout' : '' ?>">

        <?php if (CmsUserauthUtility::isAccessPermitted() && $theModule->showLoginStandardUnderVpn) : ?>
            <div class="callout-title">
                <svg class="icon">
                <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-info-circle"></use>
                </svg>
                <span class="sr-only"><?= Module::t('Alert') ?></span> <?= Module::t('Sezione visibile sotto rete locale') ?>
            </div>
            <p class="mb-5"></p>
        <?php endif; ?>

        <?php if ($theModule && $theModule->enableUserPasswordLogin) : ?>
            <?=
            $form->field($model, 'username')->textInput([
                'label' => Module::t('Indirizzo email')
            ]);
            ?>
            <?= $form->field($model, 'password')->passwordInput(); ?>

        <div class="d-md-flex flex-wrap">
            <?php if (!$theModule->hideResetPasswordLogin) : ?>
                <div class="mt-2 order-md-2 mr-auto mr-md-0">
                    <p class="mb-0"><a href="<?= '/' . $language_code . $forgotPwdUrl ?>" title="<?= Module::t('Inizia la procedura di recupero password') ?>"><?= Module::t('Hai dimenticato la password?') ?></a></p>
                </div>
            <?php endif; ?>
            <div class="order-md-1 mr-md-auto">
                <div class="form-check">
                    <?= $form->field($model, 'rememberme')->checkbox([
                        'value' => 1,
                        'id' => 'userloginform-rememberme-id'
                    ])->label('Ricordami') ?>
                </div>
            </div>
        </div>

            <?=
            Html::submitButton(
                    Module::t('userauth.controller.default.index.loginlabel'),
                    ['class' => 'btn btn-primary px-5']
            );
            ?>
            <?php
        endif;
        ?>

    </div>

    <?php
    if (($adminModule->enableRegister && $adminModule->showLogInRegisterButton) || (!$adminModule->enableRegister && !empty($adminModule->textWarningForRegisterDisabled))) {
        ?>
        <p class="mt-4 text-muted">

            <?=
            Module::t('E\' il tuo primo accesso?') . ' ' . Html::a(
                    Module::t('Registrati'),
                    null,
                    ['class' => '', 'title' => Module::t('Registrati alla piattaforma'), 'href' => '/' . $language_code . $registrationUrl]
            )
            ?>
        </p>
        <?php
    }
    ?>

    <?php $form::end(); ?>

<?php else : ?>

    <?=
    Html::a(
            Module::t('Accedi'),
            [$loginUrl],
            ['class' => 'btn btn-primary', 'title' => Module::t('Accedi alla piattaforma')]
    );
    ?>
    <?php
    if ($theModule->enableRegister) {
        ?>
        <p class="mt-4 text-muted"><?=
            Module::t('E\' il tuo primo accesso?') . ' ' . Html::a(
                    Module::t('Registrati'),
                    null,
                    ['class' => '', 'title' => Module::t('Registrati alla piattaforma'), 'href' => '/' . $language_code . $registrationUrl]
            )
            ?></p>
        <?php
    }
    ?>

<?php endif ?>

<?php if ($theModule && $theModule->enableUserPasswordLogin && $theModule->enableSPID) : ?>
    <hr class="mb-5">
<?php endif; ?>

<?php
if ($theModule->enableSPID) {
    echo $this->render(
            'parts' . DIRECTORY_SEPARATOR . 'bi-idpc',
            [
                'currentAsset' => $currentAsset,
                'hideIdpcButtonInfo' => $hideIdpcButtonInfo,
                'hideIdpcButtonTitle' => $hideIdpcButtonTitle,
                'hideIdpcButtonSubtitle' => $hideIdpcButtonSubtitle
            ]
    );
}
?>

<?php if ($theModule && $theModule->enableUserPasswordLogin && ($theModule->enableAgidLogin || $theModule->enablePuaLogin)) : ?>
    <hr class="mb-5">
<?php endif; ?>

<?php
if ($theModule->enableAgidLogin) {
    echo $this->render(
            'parts' . DIRECTORY_SEPARATOR . 'bi-idpc-agid',
            [
                'currentAsset' => $currentAsset,
                'hideIdpcButtonInfo' => $hideIdpcAgidButtonInfo,
                'hideIdpcButtonTitle' => $hideIdpcAgidButtonTitle,
                'hideIdpcButtonSubtitle' => $hideIdpcAgidButtonSubtitle
            ]
    );
}
?>
<?php
if ($theModule->enablePuaLogin) {
    echo $this->render(
            'parts' . DIRECTORY_SEPARATOR . 'bi-idpc-pua',
            [
                'currentAsset' => $currentAsset,
                'hideIdpcButtonInfo' => $hideIdpcAgidButtonInfo,
                'hideIdpcButtonTitle' => $hideIdpcAgidButtonTitle,
                'hideIdpcButtonSubtitle' => $hideIdpcAgidButtonSubtitle
            ]
    );
}
?>

<hr class="mb-5">
<?php
    echo $this->render(
        'parts' . DIRECTORY_SEPARATOR . 'bi-idpc-credentialrequest',
        [
            'currentAsset' => $currentAsset,
        ]
    );
?>

<?php if ($theModule && $theModule->enableUserPasswordLogin && ($theModule->enableAgidLogin || $theModule->enablePuaLogin)) : ?>
    <hr class="mb-5">
<?php endif; ?>

<?php
if ($theModule->enableSocial) {
    echo $this->render(
            'parts' . DIRECTORY_SEPARATOR . 'bi-social',
            [
                'currentAsset' => $currentAsset,
                'type' => 'login',
                'communityId' => null
            ]
    );
}
?>
