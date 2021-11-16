<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer">
    <h2><?= \Yii::$app->name ?></h2>
    <h3>Accedi alla piattaforma</h3>
    <hr class="bk-hrLogin">
    <p><?= Yii::t('amosplatform', 'Inserisci le credenziali per accedere'); ?></p>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?php if (\Yii::$app->params['template-amos']): ?>
                <?= $form->field($model,
                    'ruolo')->dropDownList(yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name',
                    'description')) ?>
            <?php endif; ?>
            <?= $form->field($model, 'username',
                ['inputOptions' => ['value' => 'demo']])->textInput(['readonly' => true]) ?>
            <?= $form->field($model, 'password',
                ['inputOptions' => ['value' => 'Demo1234']])->passwordInput(['readonly' => true]) ?>

            <div class="form-group">
                <a class="col-xs-12 col-sm-6 bk-askPassword" href="<?= Yii::$app->urlManager->createUrl("site/forgot-password"); ?>">Password dimenticata?</a>
                <?= Html::submitButton('Login', ['class' => 'btn btn-navigation-primary col-xs-12 col-sm-6 pull-right', 'name' => 'login-button']) ?>
            </div>

        </div>
    </div>
    <!--
<p class="bk-loginOr">oppure</p>

<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 bk-loginFb">
<span class="ti-facebook"></span><a href="#" class="">< ?= Yii::t('amosplatform', 'Connettiti con Facebook'); ?></a>
</div>
<div class="col-xs-12 col-sm-6 col-md-6 bk-loginGoogle">
<span class="ti-google"></span><a href="#" class="">< ?= Yii::t('amosplatform', 'Connettiti con Google'); ?></a>
</div>
</div>

-->
    <?php ActiveForm::end(); ?>

</div>
</div>
