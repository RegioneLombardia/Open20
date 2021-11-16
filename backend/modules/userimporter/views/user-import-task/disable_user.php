<?php

use backend\modules\userimporter\Module;
use open20\amos\admin\assets\ModuleAdminAsset;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\security
 * @category   CategoryName
 */



$this->title = Module::t('amosuserimporter', '#disable_user');
$this->params['breadcrumbs'][] = $this->title;

ModuleAdminAsset::register(Yii::$app->view);
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer loginContainer">
    <div class="body col-xs-12 nop">
        <h2 class="title-login"><?= Module::t('amosuserimporter', '#disable_user'); ?></h2>
        <h3 class="subtitle-login"><?= Module::t('amosuserimporter', '#disable_user_text'); ?></h3>
        <?php $form = ActiveForm::begin(['id' => 'disable-user',
            'action' => 'disable-user?token='. $token,
            'method' => 'get',
            ]); ?>
        <div class="row">
            <div class="col-xs-12 footer-link text-center">
                <?= Html::submitButton(Module::t('amosuserimporter', '#disable_user'), ['class' => 'btn btn-primary btn-administration-primary', 'name' => 'login-button', 'title' => Module::t('amosuserimporter', '#disable_user')]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>


</div>
