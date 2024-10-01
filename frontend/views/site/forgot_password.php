<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\security
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;
use open20\design\components\bootstrapitalia\ActiveForm;
use Yii;

$this->title = AmosAdmin::t('amosadmin', 'Password dimenticata');

$referrer = \Yii::$app->request->referrer;
if( (strpos($referrer, 'javascript') !== false) || (strpos($referrer ,\Yii::$app->params['backendUrl']) == false ) ){
    $referrer = null;
}
?>

<div class="container py-4 my-5">
    <div class="row nop">
        <div class="col-md-6 mx-auto">

        
        <h2 class="title-login"><?= AmosAdmin::t('amosadmin', '#forgot_pwd_title'); ?></h2>
        <p class="mb-4"><?= AmosAdmin::t('amosadmin', '#forgot_pwd_subtitle'); ?></p>
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