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
use open20\design\components\bootstrapitalia\ActiveForm;

$this->title = AmosAdmin::t('amosadmin', 'Password dimenticata');

$referrer = \Yii::$app->request->referrer;
if ((strpos($referrer, 'javascript') !== false) || (strpos($referrer, \Yii::$app->params['backendUrl']) == false)) {
    $referrer = null;
}
?>
<div class="header-banner uk-section- uk-visible@xl">
    <div style="background-image: url('/attachments/file/view?hash=6fe27d9b32de9df38bfec56f9f82ecc7&canCache=1');" class="uk-background-norepeat uk-background-cover uk-background-center-center uk-section">

        <div class="uk-container py-5">
            <div class="uk-container uk-grid-margin">
                <div class="container" uk-grid="">
                    <div class="row">
                    <div class="col-md-6 mx-auto">
                        <h1 class="">Hai dimenticato la password?</h1>
                        <p class="mb-4"><?= AmosAdmin::t('amosadmin', "Inserisci l'indirizzo email registrato"); ?></p>
                    </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="container py-4 my-5 qui">
    <div class="row nop">
        <div class="col-md-6 mx-auto">

            
            
            <div class="form-rounded">


                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>


                <?= $form->field($model, 'email') ?>

                <?= Html::a(AmosAdmin::t('amosadmin', 'Annulla'), (strip_tags($referrer) ?: ['/site/login']), ['class' => 'btn btn-outline-primary pull-left', 'title' => AmosAdmin::t('amosadmin', '#go_to_login_title')]) ?>
                <?= Html::submitButton(AmosAdmin::t('amosadmin', '#reset_pwd_send'), ['class' => 'btn btn-primary btn-administration-primary pull-right', 'name' => 'login-button', 'title' => AmosAdmin::t('amosadmin', '#forgot_pwd_send_title')]) ?>


                <?php ActiveForm::end(); ?>
            </div>
        </div>

    </div>
</div>