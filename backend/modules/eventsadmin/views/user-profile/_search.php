<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\FormUtility;
use open20\amos\events\models\EventGroupReferent;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\admin\models\search\UserProfileSearch $model
 * @var yii\widgets\ActiveForm $form
 */
/** @var \open20\amos\admin\controllers\UserProfileController $appController */
$appController = Yii::$app->controller;

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();

$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;
?>

<div class="user-profile-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2><?= AmosAdmin::t('amosadmin', 'Search by') ?>:</h2></div>
    <?php
    $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>
    <?= Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel); ?>
    <?= Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView')); ?>
    <?php if (count(Yii::$app->controller->module->searchListFields) == 0): ?>

        <?php if ($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_FORM) &&
            $adminModule->confManager->isVisibleField('nome', ConfigurationManager::VIEW_TYPE_FORM)) {
            ?>
            <div class="col-sm-6 col-lg-3">
                <?= $form->field($model, 'nome')->textInput(['placeholder' => AmosAdmin::t('amosadmin', 'Search by name')]) ?>
            </div>
        <?php } ?>

        <?php if ($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_FORM) &&
            $adminModule->confManager->isVisibleField('cognome', ConfigurationManager::VIEW_TYPE_FORM)) {
            ?>
            <div class="col-sm-6 col-lg-3">
                <?= $form->field($model, 'cognome')->textInput(['placeholder' => AmosAdmin::t('amosadmin', 'Search by surname')]) ?>
            </div>
        <?php } ?>


        <?php if ($adminModule->confManager->isVisibleBox('box_dati_accesso', ConfigurationManager::VIEW_TYPE_FORM) &&
            $adminModule->confManager->isVisibleField('username', ConfigurationManager::VIEW_TYPE_FORM)) {
            ?>
            <!--            <div class="col-sm-6 col-lg-3">-->
            <!--                --><?php //echo $form->field($model, 'username')->textInput(['placeholder' => AmosAdmin::t('amosadmin', 'Search by username')]) ?>
            <!--            </div>-->
        <?php } ?>

        <?php if ($adminModule->confManager->isVisibleBox('box_dati_contatto', ConfigurationManager::VIEW_TYPE_FORM) &&
            $adminModule->confManager->isVisibleField('email', ConfigurationManager::VIEW_TYPE_FORM)) {
            ?>
            <div class="col-sm-6 col-lg-3">
                <?= $form->field($model, 'email')->textInput(['placeholder' => AmosAdmin::t('amosadmin', 'Search by email')]) ?>
            </div>
        <?php } ?>
        <div class="col-sm-6 col-lg-3">
            <?= $form->field($model, 'codice_fiscale')->textInput(['placeholder' => AmosAdmin::t('amosadmin', 'Codice fiscale')]) ?>
        </div>


        <?php
        if (\Yii::$app->user->can('SUPER_USER_EVENT')) { ?>
            <div class="col-sm-6 col-lg-3">
                <?= $form->field($model, 'dg_id')->widget(\kartik\select2\Select2::className(),[
                        'data' => \yii\helpers\ArrayHelper::map(EventGroupReferent::find()->all(),'id', 'denominazione'),
                    'options' => ['placeholder' => AmosAdmin::t('amosadmin', 'Seleziona...')]
                ])->label(AmosAdmin::t('amosadmin', 'DG')) ?>
            </div>
        <?php } ?>

        <?php if ($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_FORM) &&
            $adminModule->confManager->isVisibleField('sesso', ConfigurationManager::VIEW_TYPE_FORM)) {
            ?>
            <!--            <div class="col-sm-6 col-lg-3">-->
            <!--                --><?php // $form->field($model, 'sesso')->dropDownList($model->getSexValuesForSelect(),
//                    ['prompt' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...', 'disabled' => false])->hint(false);
//                ?>
            <!--            </div>-->
        <?php } ?>

        <?php if ($adminModule->confManager->isVisibleBox('box_prevalent_partnership', ConfigurationManager::VIEW_TYPE_FORM) &&
            $adminModule->confManager->isVisibleField('prevalent_partnership_id', ConfigurationManager::VIEW_TYPE_FORM)) {
            ?>
            <div class="col-sm-6 col-lg-3">
                <?=
                $form->field($model, 'prevalent_partnership_id')->dropDownList($appController->getAllOrganizationsForSelect(),
                    ['prompt' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...', 'disabled' => false])
                    ->label($model->getAttributeLabel('prevalentPartnership'));
                ?>
            </div>
        <?php } ?>

        <?php if (!$adminModule->bypassWorkflow) { ?>
            <div class="col-sm-6 col-lg-3">
                <?= $form->field($model, 'userProfileStatus')->dropDownList($appController->getWorkflowStatuses($model),
                    ['prompt' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...', 'disabled' => false]);
                ?>
            </div>

            <div class="col-sm-6 col-lg-3">
                <!--                --><?php //echo
                //                $form->field($model, 'validato_almeno_una_volta')->dropDownList(FormUtility::getBooleanFieldsValues(),
                //                    ['prompt' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...', 'disabled' => false])
                //                    ->label(AmosAdmin::t('amosadmin', 'Validated at least once') . '?');
                //                ?>
            </div>
        <?php } ?>

        <?php if ($adminModule->confManager->isVisibleBox('box_facilitatori', ConfigurationManager::VIEW_TYPE_FORM) &&
            $adminModule->confManager->isVisibleField('facilitatore_id', ConfigurationManager::VIEW_TYPE_FORM)) {
            ?>
            <div class="col-sm-6 col-lg-3">
                <?=
                $form->field($model, 'facilitatore_id')->dropDownList($appController->getFacilitatorsForSelect(),
                    ['prompt' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...', 'disabled' => false])
                    ->label(AmosAdmin::t('amosadmin', 'Users having as facilitator'));
                ?>
            </div>

            <div class="col-sm-6 col-lg-3">
                <?=
                $form->field($model, 'isFacilitator')->dropDownList(FormUtility::getBooleanFieldsValues(),
                    ['prompt' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...', 'disabled' => false])
                    ->label(AmosAdmin::t('amosadmin', 'Is facilitator') . '?');
                ?>
            </div>
        <?php } ?>

        <?php
        $organizationModuleName = $adminModule->getOrganizationModuleName();
        if (($organizationModuleName == 'organizations') && !is_null(Yii::$app->getModule($organizationModuleName))) {
            ?>
            <div class="col-sm-6 col-lg-3">
                <?=
                $form->field($model, 'isOperatingReferent')->dropDownList(FormUtility::getBooleanFieldsValues(),
                    ['prompt' => AmosAdmin::t('amosadmin', 'Select/Choose') . '...', 'disabled' => false])
                    ->label(AmosAdmin::t('amosadmin', 'Is operating referent') . '?');
                ?>
            </div>
        <?php } ?>

    <?php else: ?>
        <?php foreach (Yii::$app->controller->module->searchListFields as $filed): ?>
            <div class="col-sm-6 col-lg-3">
                <?= $form->field($model, $filed) ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(
                AmosAdmin::t('amosadmin', 'Reset'),
                [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-secondary'])
            ?>
            <?= Html::submitButton(AmosAdmin::t('amosadmin', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--a><p class="text-center">< ?= AmosAdmin::tHtml('amosadmin', 'Ricerca avanzata') ?><br>
      < ?= AmosIcons::show('caret-down-circle'); ?>
    </p></a-->

    <?php ActiveForm::end(); ?>
</div>