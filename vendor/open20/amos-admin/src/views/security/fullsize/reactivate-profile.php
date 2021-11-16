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
use open20\amos\core\icons\AmosIcons;

ModuleAdminAsset::register(Yii::$app->view);
/**
 * @var yii\web\View $this
 * @var yii\bootstrap\ActiveForm $form
 * @var \open20\amos\admin\models\LoginForm $model
 */

$this->title = AmosAdmin::t('amosadmin', 'Reactivate Profile');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'id' => 'reactivate-profile-form'
]); ?>
<div id="bk-formDefaultLogin" class="loginContainerFullsize">
    <div class="login-block reactivate-block col-xs-12 nop">
        <div class="login-body">
            <h2 class="title-login"><?= AmosAdmin::t('amosadmin', '#fullsize_reactivate_profile'); ?></h2>
            <?php if (\Yii::$app->request->get() && array_key_exists("userdisabled", \Yii::$app->request->get())) { ?>
                <h3 class="title-login"><?= AmosAdmin::t('amosadmin', '#userdisabled_profile_subtitle'); ?></h3>
            <?php } else { ?>
                <h3 class="title-login"><?= AmosAdmin::t('amosadmin', '#fullsize_reactivate_profile_subtitle'); ?></h3>
            <?php } ?>

            <div class="row">
                <div class="col-xs-12">
                    <?= $form->field($model, 'email')->textInput([
                            'maxlength' => true,
                            'placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_email_reactivate')
                    ])->label('')?>
                    <?= AmosIcons::show('mail', '', AmosIcons::IC) ?>
                </div>
                <div class="col-xs-12">
                    <?= $form->field($model, 'message')->textarea([
                            'maxlength' => true,
                            'rows' => 6,
                            'placeholder' => AmosAdmin::t('amosadmin', '#fullsize_field_msg_reactivate')
                    ])->label('')?>
                </div>
                <div class="col-xs-12 action">
                    <?= Html::submitButton(AmosAdmin::t('amosadmin', '#reactivate_profile_send'), ['class' => 'btn btn-secondary', 'title' => AmosAdmin::t('amosadmin', '#reactivate_profile_send_title')]) ?>
                    <?php if (\Yii::$app->request->get() && array_key_exists("userdisabled", \Yii::$app->request->get())) { ?>
                        <?= Html::a(AmosAdmin::t('amosadmin', '#go_to_login'), ['/'.AmosAdmin::getModuleName().'/security/login'], ['class' => 'btn btn-navigation-primary', 'title' => AmosAdmin::t('amosadmin', '#go_to_login_title')]) ?>
                    <?php } else { ?>
                        <?= Html::a(AmosAdmin::t('amosadmin', '#go_to_register'), ['/'.AmosAdmin::getModuleName().'/security/register'], ['class' => 'btn btn-navigation-primary', 'title' => AmosAdmin::t('amosadmin', '#go_to_register_title')]) ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>