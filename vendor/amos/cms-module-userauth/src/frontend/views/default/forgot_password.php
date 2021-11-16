<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\security
 * @category   CategoryName
 */

use amos\userauth\frontend\Module;
use open20\amos\core\helpers\Html;
use open20\design\components\bootstrapitalia\ActiveForm;

$this->title = Module::t('Password dimenticata');

$referrer = \Yii::$app->request->referrer;
if ((strpos($referrer, 'javascript') !== false) || (strpos($referrer, \Yii::$app->params['backendUrl']) == false)) {
    $referrer = null;
}
?>

<div class="container py-4 my-5">
    <div class="row nop">
        <div class="col-md-6 mx-auto">


            <h2 class="title-login"><?= Module::t('#forgot_pwd_title'); ?></h2>
            <p class="mb-4"><?= Module::t('#forgot_pwd_subtitle'); ?></p>
            <div class="form-rounded">

                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' =>
                    [
                        'class' => 'userauth-reset-pwd-form needs-validation form-rounded'
                    ]
                ]);
                ?>

                <?= $form->field($model, 'email') ?>

                <?= Html::a(Module::t('Annulla'), (strip_tags($referrer) ?: ['/site/login']), ['class' => 'btn btn-outline-primary pull-left', 'title' => Module::t('#go_to_login_title')]) ?>
                <?= Html::submitButton(Module::t('#reset_pwd_send'), ['class' => 'btn btn-primary btn-administration-primary pull-right', 'name' => 'login-button', 'title' => Module::t('#forgot_pwd_send_title')]) ?>


                <?php ActiveForm::end(); ?>
            </div>
        </div>

    </div>
</div>