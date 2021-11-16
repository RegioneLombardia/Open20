<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use kartik\password\PasswordInput;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'First access';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer body">
    <h2 class="title-login nom"><?= Html::encode($this->title) ?></h2>
    <p class="subtitle-login"><?= Yii::t('amosplatform', 'Scegli le tue credenziali di accesso'); ?></p>
    <div class="clearfix"></div>
        <?php
        $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'form-horizontal', 'autocomplete' => 'off'],
        ])
        ?>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'username')->textInput() ?>
                <div class="form-group pull-right">
                    <span class="pull-right"><?= Yii::t('amosplatform', 'Livello sicurezza') ?></span>
                </div>
                <?=
                $form->field($model, 'password')->widget(PasswordInput::classname(), [
                    'language' => 'it',
                    'pluginOptions' => [
                        'showMeter' => true,
                        'toggleMask' => true,
                        'language' => 'it'
                    ]]);

                ?>
                <div class="form-group pull-right">
                    <span><?= Yii::t("amosplatform", "(Almeno 8 Caratteri di cui 1 lettera Minuscola, 1 Maiuscola e 1 Numero)") ?></span>
                </div>
                <?= $form->field($model, 'ripetiPassword')->passwordInput() ?>
                <?= $form->field($model, 'token')->hiddenInput()->label(false) ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('amosplatform', 'Salva'), ['class' => 'btn bk-btnLogin col-xs-12 btn-primary', 'name' => 'primo-accesso-button']) ?>
                </div>
            </div>
        </div>
            <?php ActiveForm::end(); ?>
    </div>
</div>
