<?php

use amos\userauth\frontend\Module;
use open20\amos\admin\AmosAdmin;
use luya\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $socialAuthModule \open20\amos\socialauth\Module */
$socialAuthModule = Yii::$app->getModule('socialauth');

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());

/** @var Module $module */
$module = Module::instance();

$socialMatch   = Yii::$app->session->get('social-match');
$socialProfile = Yii::$app->session->get('social-profile');
?>

<?php $form = ActiveForm::begin(); ?>

<?php if ($module && $module->enableUserPasswordLogin) : ?>
    <?= $form->field($model, 'username'); ?>
    <?= $form->field($model, 'password')->passwordInput(); ?>
    <?= Html::submitButton(Module::t('userauth.controller.default.index.loginlabel')); ?>
<?php endif; ?>

<?php if ($socialAuthModule && $socialAuthModule->enableLogin && !$socialMatch) : ?>
    <div class="social-block col-xs-12 nop">
    <?=
    $this->render('parts'.DIRECTORY_SEPARATOR.'social',
        [
        'type' => 'login',
        'communityId' => null
    ]);
    ?>
    </div>
<?php endif; ?>

<?php if ($socialAuthModule && $socialAuthModule->enableSpid) : ?>
    <div class="spid-block col-xs-12 nop">
        <?= $this->render('parts'.DIRECTORY_SEPARATOR.'spid'); ?>
    </div>
<?php endif; ?>

<?php $form::end(); ?>
