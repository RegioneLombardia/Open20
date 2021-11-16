<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile\boxes
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\core\icons\AmosIcons;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;
$enableValidationEmail = $adminModule->enableValidationEmail;

$readonlyEmail = false;
if ($enableValidationEmail) {
    $divClasses = 'col-sm-12 nop';
    if (!$model->isNewRecord) {
        $readonlyEmail = true;
    }
}
if (\Yii::$app->user->can('ADMIN')) {
    $enableValidationEmail = false;
}

?>
<section>
    <!--    <h2>-->
    <!--        < ?= AmosIcons::show('phone'); ?>-->
    <!--        < ?= AmosAdmin::tHtml('amosadmin', 'Dati di Contatto'); ?>-->
    <!--    </h2>-->
    <div class="row">
        <?php if ($adminModule->confManager->isVisibleField('email', ConfigurationManager::VIEW_TYPE_FORM)): ?>
            <div class="<?= $divClasses ?>">
                <div class="col-lg-6 col-sm-6">
                    <?php if ($readonlyEmail && $enableValidationEmail) { ?>
                        <div class="form-group">
                            <label class="control-label"><?= $model->getAttributeLabel('email') ?></label>
                            <p class="m-t-10"><?= $user->email ?></p>
                        </div>
                        <div style="display:none"><?= $form->field($user, 'email')->hiddenInput()->label(false) ?></div>
                    <?php } else { ?>
                        <?= $form->field($user, 'email')->textInput(['readonly' => $readonlyEmail])
                            ->label($model->getAttributeLabel('email') . ' ' . AmosIcons::show('lock', ['title' => AmosAdmin::t('amosadmin', '#confidential')])) ?>
                    <?php } ?>
                </div>
                <?php if ($enableValidationEmail) { ?>
                    <div class="col-lg-6 col-sm-6 m-t-25">
                        <?php echo \open20\amos\core\helpers\Html::a('modifica', ['modify-email', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>
        <?php if ($adminModule->confManager->isVisibleField('telefono', ConfigurationManager::VIEW_TYPE_FORM)): ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'telefono')->textInput(['maxlength' => true, 'readonly' => false])
                    ->label($model->getAttributeLabel('telefono') . ' ' . AmosIcons::show('lock', ['title' => AmosAdmin::t('amosadmin', '#confidential')])) ?>
            </div>
        <?php endif; ?>
        <?php if ($adminModule->confManager->isVisibleField('cellulare', ConfigurationManager::VIEW_TYPE_FORM)): ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'cellulare')->textInput(['maxlength' => true, 'readonly' => false]) ?>
            </div>
        <?php endif; ?>
        <?php if ($adminModule->confManager->isVisibleField('email_pec', ConfigurationManager::VIEW_TYPE_FORM)): ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'email_pec')->textInput() ?>
            </div>
        <?php endif; ?>
    </div>
</section>
