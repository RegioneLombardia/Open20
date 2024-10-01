<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\userauth\frontend\views
 * @category   CategoryName
 */

use amos\userauth\frontend\Module;
use open20\amos\admin\AmosAdmin;
use open20\design\components\bootstrapitalia\ActiveForm;
use luya\helpers\Html;

$this->title = Module::t('#title_reset_password');
$textPwd = Module::t('#password_rules');

$form = ActiveForm::begin([
    'options' => [
        'class' => 'userauth-reset-pwd-form needs-validation form-rounded'
    ]
]);
?>
<div class="container py-4 my-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <h1 class="h2"><?= Module::t('#reset_password') ?></h1>
            <p class="mb-5"><?= Module::t('#reset_password_fill_the_form') ?></p>

            <div class="form-rounded">
            <?= $form->field($model, 'password')->PasswordInput([
                'label' => Module::t('Nuova password'),
                'placeholder' => Module::t('inserisci la nuova password'),
                'helperTooltip' => $textPwd,
                'enableStrengthMeter' => true,
                'data-enter-pass' => ''
            ])
            ?>

            <?= $form->field($model, 'ripetiPassword')->PasswordInput([
                'label' => Module::t('Conferma password'),
                'placeholder' => Module::t('conferma la nuova password'),
                //'enableStrengthMeter' => true
            ])
            ?>

                <div class="">
                    <?php if (!empty($isFirstAccess) && $isFirstAccess) : ?>
                        <div class="cookie-privacy">
                            <?=
                                Html::a(
                                    AmosAdmin::t('amosadmin', '#cookie_policy_message'),
                                    \Yii::$app->params['linkConfigurations']['cookiePolicyLinkCommon'],
                                    [
                                        'title' => AmosAdmin::t('amosadmin', '#cookie_policy_title'),
                                        'target' => '_blank'
                                    ]
                                )
                            ?>
                            <?= Html::tag('p', AmosAdmin::t('amosadmin', '#cookie_policy_content')) ?>
                            <div class="">
                                <?= $form->field($model, 'privacy')->radioList([
                                    1 => AmosAdmin::t('amosadmin', '#cookie_policy_ok'),
                                    0 => AmosAdmin::t('amosadmin', '#cookie_policy_not_ok')
                                ])->label('Privacy check',['class'=>'control-label sr-only']); ?>
                            </div>
                        </div>
                    <?php endif ?>
                </div>

            <?= $form->field($model, 'token')->hiddenInput()->label(false) ?>

            <?= Html::submitButton(
                'Aggiorna',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'reset-pwd-button',
                    'title' => 'Conferma aggiornamento password']
            )
            ?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
