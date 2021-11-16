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
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->controller->module;

$checkCF = ($adminModule->confManager->isVisibleField('codice_fiscale', ConfigurationManager::VIEW_TYPE_FORM));
$checkPIVA = ($adminModule->confManager->isVisibleField('partita_iva', ConfigurationManager::VIEW_TYPE_FORM));
$checkIBAN = ($adminModule->confManager->isVisibleField('iban', ConfigurationManager::VIEW_TYPE_FORM));

?>
<?php if ($checkCF || $checkPIVA || $checkIBAN): ?>
    <section class="section-data">
        <h2>
            <?= AmosIcons::show('case'); ?>
            <?= AmosAdmin::tHtml('amosadmin', 'Dati Fiscali e Amministrativi') ?>
        </h2>
        <!--
        <div class="bk-testoBoxInfo">
            <p>< ?= AmosAdmin::tHtml('amosadmin', "I dati amministrativi consentono la fatturazione e il pagamento delle parcelle, assicurarsi che i dati inseriti siano corretti."); ?></p>
        </div>-->

        <div class="row">
            <?php if ($checkCF): ?>
                <?php if ($adminModule->enableDlSemplification): ?>
                    <div class="col-lg-6 col-sm-6 nop">
                        <div class="form-group">
                            <label class="control-label"><?= $model->getAttributeLabel('codice_fiscale') ?></label>
                            <p class="m-l-5 m-t-10"><?= (!empty($model->codice_fiscale) ? $model->codice_fiscale : AmosAdmin::t('amosadmin', "Non inserito")) ?></p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6" style="display:none">
                        <?php $form->field($model, 'codice_fiscale')->hiddenInput()->label(false); ?>
                    </div>
                <?php else: ?>
                    <div class="col-lg-6 col-sm-6">
                        <?= $form->field($model, 'codice_fiscale')->textInput(['maxlength' => true, 'data-message' => Html::error($model, 'codice_fiscale')]) ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($checkPIVA): ?>
                <div class="col-lg-6 col-sm-6">
                    <?= $form->field($model, 'partita_iva')->textInput(['maxlength' => true, 'data-message' => Html::error($model, 'partita_iva')]) ?>
                </div>
            <?php endif; ?>
            <?php if ($checkIBAN): ?>
                <div class="col-lg-6 col-sm-6">
                    <?= $form->field($model, 'iban')->textInput(['maxlength' => true, 'data-message' => Html::error($model, 'iban')]) ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
