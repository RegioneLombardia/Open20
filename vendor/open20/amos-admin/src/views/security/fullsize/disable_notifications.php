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

$this->title = AmosAdmin::t('amosadmin', '#disable_notification');
$this->params['breadcrumbs'][] = $this->title;

ModuleAdminAsset::register(Yii::$app->view);
?>

<div id="bk-formDefaultLogin" class="loginContainerFullsize">
    <div class="login-block disablenotify-block col-xs-12 nop">
        <div class="login-body">
            <h2 class="title-login"><?= AmosAdmin::t('amosadmin', '#disable_notification'); ?></h2>
            <h3 class="title-login"><?= AmosAdmin::t('amosadmin', '#disable_notification_text'); ?></h3>
            <?php $form = ActiveForm::begin(['id' => 'disable-notifications']); ?>
            <div class="row">
                <div class="col-xs-12">
                    <?= Html::hiddenInput('user_id', $token) ?>
                </div>
                <div class="col-xs-12 action">
                    <?= Html::submitButton(AmosAdmin::t('amosadmin', '#disable_notifications_send'), ['class' => 'btn btn-primary btn-administration-primary', 'name' => 'login-button', 'title' => AmosAdmin::t('amosadmin', '#disable_notifications_send')]) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
