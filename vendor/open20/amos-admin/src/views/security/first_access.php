<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

//use kartik\password\PasswordInput;
use open20\amos\core\forms\PasswordInput;
use open20\amos\core\forms\ActiveForm;
use yii\helpers\Html;
use open20\amos\admin\AmosAdmin;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'First access';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="bk-formDefaultLogin" class="bk-loginContainer loginContainer">
    <div class="body col-xs-12">
        <h2 class="title-login"><?= AmosAdmin::t('amosadmin', '#first_access_title'); ?></h2>
        <h3 class="subtitle-login"><?= AmosAdmin::t('amosadmin', '#first_access_subtitle'); ?></h3>
        <?php
        $form = ActiveForm::begin(
            [
                'id' => 'login-form',
                'options' => ['autocomplete' => 'off'],
            ]
        )
        ?>
        <div class="row">
            <div class="col-xs-12 nop">
                <div class="m-b-5"><?= $form->field($model, 'username')->textInput() ?></div>
                <div class="level-security-fa">
                    <span><?= Yii::t('amosadmin', 'Livello sicurezza') ?></span>
                </div>
                <?=
                $form->field($model, 'password')->widget(
                    PasswordInput::classname(),
                    [
                        'language' => 'it',
                        'options' => [
                            'autocomplete' => 'off'
                        ],
                        'pluginOptions' => [
                            'showMeter' => true,
                            'toggleMask' => true,
                            'language' => 'it'
                        ]
                    ]
                );

                ?>
                <div class="form-group">
                    <span><?= Yii::t("amosadmin", "#first_access_pwd_detail") ?></span>
                </div>
                <?= $form->field($model, 'ripetiPassword')->passwordInput(
                    [
                        'autocomplete' => 'off'
                    ]
                ) ?>
                <?php if (!empty($isFirstAccess) && $isFirstAccess) { ?>
                    <div class="text-bottom col-xs-12 nop">
                        <?= Html::a(
                            AmosAdmin::t('amosadmin', '#cookie_policy_message'),
                            '/site/privacy',
                            ['title' => AmosAdmin::t('amosadmin', '#cookie_policy_title'), 'target' => '_blank']
                        ) ?>
                        <?= Html::tag('p', AmosAdmin::t('amosadmin', '#cookie_policy_content')) ?>
                        <?= $form->field($model, 'privacy')->radioList(
                            [
                                1 => AmosAdmin::t('amosadmin', '#cookie_policy_ok'),
                                0 => AmosAdmin::t('amosadmin', '#cookie_policy_not_ok')
                            ]
                        ); ?>
                    </div>
                <?php } ?>
                <?= $form->field($model, 'token')->hiddenInput()->label(false) ?>
                <div class="form-group footer nop">
                    <?= Html::submitButton(
                        AmosAdmin::t('amosadmin', '#text_button_login'),
                        [
                            'class' => 'btn btn-primary btn-administration-primary pull-right',
                            'name' => 'primo-accesso-button',
                            'title' => AmosAdmin::t('amosadmin', '#text_button_login')
                        ]
                    ) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
