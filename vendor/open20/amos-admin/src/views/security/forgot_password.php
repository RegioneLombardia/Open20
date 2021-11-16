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

$this->title = AmosAdmin::t('amosadmin', 'Password dimenticata');
$this->params['breadcrumbs'][] = $this->title;

$referrer = \Yii::$app->request->referrer;
if( (strpos($referrer, 'javascript') !== false) || (strpos($referrer ,\Yii::$app->params['backendUrl']) == false ) ){
    $referrer = null;
}
ModuleAdminAsset::register(Yii::$app->view);
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer loginContainer">
    <div class="body col-xs-12 nop">
        <h2 class="title-login"><?= AmosAdmin::t('amosadmin', '#forgot_pwd_title'); ?></h2>
        <h3 class="subtitle-login"><?= AmosAdmin::t('amosadmin', '#forgot_pwd_subtitle'); ?></h3>
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'email') ?>
            </div>
            <div class="col-xs-12 footer-link">
                <?= Html::submitButton(AmosAdmin::t('amosadmin', '#forgot_pwd_send'), ['class' => 'btn btn-primary btn-administration-primary pull-right', 'name' => 'login-button', 'title' => AmosAdmin::t('amosadmin', '#forgot_pwd_send_title')]) ?>
                <?= Html::a(AmosAdmin::t('amosadmin', '#go_to_login'), (strip_tags($referrer) ?: ['/'.AmosAdmin::getModuleName().'/security/login']), ['class' => 'btn btn-secondary pull-left', 'title' => AmosAdmin::t('amosadmin', '#go_to_login_title')]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>


</div>