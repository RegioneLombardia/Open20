<?php

use open20\design\components\bootstrapitalia\ActiveForm;
use open20\design\components\bootstrapitalia\Input;
use preference\userprofile\models\PreferenceUserTargetAttribute;
use preference\userprofile\utility\TargetAttributeUtility;
use preference\userprofile\utility\TargetTagUtility;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\View;

?>
<div class="row variable-gutters my-5">
    <!-- START SIDEBAR -->
    <div class="col-lg-3 affix-parent d-none d-lg-block">
        <div class="sidebar-wrapper it-line-right-side sidebar-preference h-100 pt-5">
        </div>
    </div>
    <!-- START MAIN -->
    <div class="col-lg-9 mt-4">
        <div class="mb-5">
            <h1 class="h2">Aggiornamento email</h1>
            <p>Compila i seguenti campi per aggiornare l’indirizzo email</p>
            <?php
            $form = ActiveForm::begin([
                'enableClientScript' => true,
                'options' => [
                    'id' => 'email-form',
                    'class' => 'needs-validation form-rounded',
                    'enctype' => 'multipart/form-data',
                ],
            ]);
            ?>

            <div class="form-group mt-5">
                <?= $form->field($model, 'actualEmail')->textInput(); ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'email')->textInput(); ?>
            </div>

            <?php
            echo Html::a(
                'Annulla',
                Url::to(['user-profile']),
                ['class' => 'btn btn-outline-primary']
            );
            ?>

            <?php
            echo Html::submitButton(
                'Aggiorna',
                ['class' => 'btn btn-primary']
            );
            ?>

            <?php

            ActiveForm::end();


            $this->registerJs(
                <<<JS


JS
,
                View::POS_READY
            );
            ?>
        </div>
    </div>
</div>