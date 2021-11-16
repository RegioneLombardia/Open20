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

$this->title = AmosAdmin::t('amosadmin', 'Password dimenticata');
$this->params['breadcrumbs'][] = $this->title;

/*
pr('test');
die();
*/

$referrer = \Yii::$app->request->referrer;

if( (strpos($referrer, 'javascript') !== false) || (strpos($referrer ,\Yii::$app->params['backendUrl']) == false ) ){
    $referrer = null;
}

/*
pr($referrer);
die();
*/

ModuleAdminAsset::register(Yii::$app->view);
?>

<div id="bk-formDefaultLogin" class="loginContainerFullsize">
    <div class="login-block forgotpwd-block col-xs-12 nop">
        <div class="login-body">
            <h2 class="title-login"><?= AmosAdmin::t('amosadmin', '#fullsize_forgotpwd'); ?></h2>
            <h3 class="title-login"><?= AmosAdmin::t('amosadmin', '#fullsize_forgotpwd_subtitle'); ?></h3>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <div class="row">
                <div class="col-xs-12">
                    <?= $form->field($model, 'email')->textInput(['placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_email_forgotpwd')])->label('') ?>
                    <?= AmosIcons::show('mail', '', AmosIcons::IC) ?>
                </div>
                <div class="col-xs-12 action">
                    <?= Html::submitButton(AmosAdmin::t('amosadmin', '#forgot_pwd_send'), ['class' => 'btn btn-navigation-primary', 'name' => 'login-button', 'title' => AmosAdmin::t('amosadmin', '#forgot_pwd_send_title')]) ?>
                    <?= Html::a(AmosAdmin::t('amosadmin', '#go_to_login'), (strip_tags($referrer) ?: ['/'.AmosAdmin::getModuleName().'/security/login']), ['class' => 'btn btn-navigation-primary', 'title' => AmosAdmin::t('amosadmin', '#go_to_login_title')]) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>