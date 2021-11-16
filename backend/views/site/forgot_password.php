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


$this->title = 'Password dimenticata';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer">
    <h2><?= Html::encode($this->title) ?></h2>
    <hr class="bk-hrLogin">
    <p><?= Yii::t('amosplatform', 'Inserisci lo username di registrazione oppure il codice fiscale'); ?></p>

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'username') ?>
            <p class="text-center"><?= Yii::t('amosplatform', ' - oppure - '); ?></p>
            <?= $form->field($model, 'codice_fiscale') ?>

            <div class="form-group">
                <?= Html::submitButton('Invia le nuove credenziali', ['class' => 'btn bk-btnLogin col-xs-12 col-sm-6', 'name' => 'login-button']) ?>
            </div>
            <a class="col-xs-12 col-sm-12 text-right bk-askPassword" href="login">Accedi</a>


            <div class="clear"></div>

            <hr class="bk-hrLogin">
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
