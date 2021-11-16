<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */



use open20\amos\core\helpers\Html;
use open20\design\components\bootstrapitalia\ActiveForm;

$this->title = 'Reset password';
$textPwd = 'La password deve contenere almeno: 8 caratteri, lettere maiuscole e minuscole ed almeno un numero';
?>
<div class="container py-4 my-5">
    <div class="row nop">
        <div class="col-md-6 mx-auto">
            <h1 class="h2">Aggiornamento password</h1>
            <p class="mb-5">Compila i seguenti campi per aggiornare la password</p>
            <div class="form-rounded">


                <?php
                $form = ActiveForm::begin([
                    'id' => 'login-form',
                ])
                ?>

                <?= $form->field($model, 'password')->passwordInput([
                    'label' => Yii::t('Nuova password'),
                    'placeholder' => Yii::t('inserisci la nuova password'),
                    'helperTooltip' => $textPwd,
                    'enableStrengthMeter' => true,
                    'data-enter-pass' => ''
                ])
                ?>

                <?= $form->field($model, 'ripetiPassword')->passwordInput([
                    'label' => Yii::t('Conferma password'),
                    'placeholder' => Yii::t('conferma la nuova password'),
                    //'enableStrengthMeter' => true
                ])
                ?>

                <?= $form->field($model, 'token')->hiddenInput()->label(false) ?>

                <?= Html::submitButton('Aggiorna', ['class' => 'btn btn-primary', 'name' => 'first-access-button', 'title' => 'Conferma aggiornamento password']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
