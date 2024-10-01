<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-wizard
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\DocumentiCategorie;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use yii\redactor\widgets\Redactor;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 * @var yii\widgets\ActiveForm $form
 */

$this->title = AmosDocumenti::t('amosdocumenti', '#documents_wizard_page_title');
$enableCategories = AmosDocumenti::instance()->enableCategories;

/** @var AmosDocumenti $documentsModule */
$documentsModule = AmosDocumenti::instance();

/** @var DocumentiCategorie $documentiCategorieModel */
$documentiCategorieModel = $documentsModule->createModel('DocumentiCategorie');

?>

<div class="document-wizard-details">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'document-wizard-form',
            'class' => 'form',
            'enctype' => 'multipart/form-data', // To load images
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>
    <?= $form->errorSummary($model, ['class' => 'alert-danger alert fade in', 'role' => 'alert']); ?>
    <?= $form->field($model, 'parent_id')->hiddenInput()->label(false) ?>
    <section>
        <div class="row">
            <div class="col-lg-8 col-sm-8">
                <?= $form->field($model, 'titolo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'sottotitolo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'descrizione_breve')->textarea(['maxlength' => true, 'rows' => 3]) ?>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="col-lg-12 col-sm-12 pull-left">
                    <?= $form->field($model, 'documentMainFile')->widget(AttachmentsInput::classname(), [
                        'options' => [
                            'multiple' => FALSE,
                            'accept' => ".csv, .pdf, .txt, .doc, .docx, .xls, .xlsx, .rtf, .odt",
                        ],
                        'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                            'maxFileCount' => 1, // Client max files,
                            'showRemove' => false,
                            'indicatorNew' => false,
                            'allowedPreviewTypes' => false,
                            'previewFileIconSettings' => false,
                            'overwriteInitial' => false,
                            'layoutTemplates' => false
                        ]
                    ]) ?>
                    <?php
                    if (!empty($documento)):
                        ?>
                        <?= $documento->filename ?>
                        <?= Html::a(AmosIcons::show('download', ['class' => 'btn btn-tool-secondary']), ['/documenti/documenti/download-documento-principale', 'id' => $model->id], [
                        'title' => 'Download file',
                        'class' => 'bk-btnImport'
                    ]); ?>
                        <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'descrizione')->widget(Redactor::className(), [
                    'clientOptions' => [
                        'placeholder' => AmosDocumenti::t('amosdocumenti', '#documents_text_placeholder'),
                        'buttonsHide' => [
                            'image',
                            'file'
                        ],
                        'lang' => substr(Yii::$app->language, 0, 2)
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <?php if($enableCategories): ?>
                <div class="col-lg-6 col-sm-6">
                    <?= $form->field($model, 'documenti_categorie_id')->widget(Select::className(), [
                        'options' => ['placeholder' => AmosDocumenti::t('amosdocumenti', 'Digita il nome della categoria'), 'id' => 'documenti_categorie_id-id', 'disabled' => FALSE],
                        'data' => ArrayHelper::map($documentiCategorieModel::find()->orderBy('titolo')->all(), 'id', 'titolo')
                    ]); ?>
                </div>
            <?php endif; ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'comments_enabled')->widget(Select::className(), [
                    'auto_fill' => true,
                    'data' => [
                        '0' => AmosDocumenti::t('amosdocumenti', 'No'),
                        '1' => AmosDocumenti::t('amosdocumenti', 'Si')
                    ],
                    'options' => [
                        'prompt' => AmosDocumenti::t('amosdocumenti', 'Seleziona'),
                        'disabled' => false
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <?php
            if (Yii::$app->getModule('documenti')->params['site_publish_enabled']) { ?>
                <div class="col-lg-6 col-sm-6">
                    <?=
                    $form->field($model, 'primo_piano')->dropDownList([
                        '0' => 'No',
                        '1' => 'Si'
                    ], [

                            'prompt' => AmosDocumenti::t('amosdocumenti', 'Seleziona...'),
                            'disabled' => false,
                            'onchange' => '
                        if($(this).val() == 1) $(\'#documenti-in_evidenza\').prop(\'disabled\', false);
                        if($(this).val() == 0) { 
                            $(\'#documenti-in_evidenza\').prop(\'disabled\', true);
                            $(\'#documenti-in_evidenza\').val(0);
                        }
                        '
                        ]
                    )
                    ?>
                </div>
            <?php } ?>
            <?php
            if (Yii::$app->getModule('documenti')->params['site_featured_enabled']) { ?>
                <div class="col-lg-6 col-sm-6">
                    <?=
                    $form->field($model, 'in_evidenza')->dropDownList([
                        '0' => 'No',
                        '1' => 'Si'
                    ], [
                            'prompt' => AmosDocumenti::t('amosdocumenti', 'Seleziona...'),
                            'disabled' => ($model->primo_piano == 0) ? true : false
                        ]
                    )
                    ?>
                </div>
            <?php } ?>
            <?php
            $module = \Yii::$app->getModule(AmosDocumenti::getModuleName());
            if($module->hidePubblicationDate == false) {?>
                <div class="col-lg-6 col-sm-6">
                    <?= $form->field($model, 'data_pubblicazione')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE
                    ]) ?>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <?= $form->field($model, 'data_rimozione')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE
                    ]) ?>
                </div>
            <?php } ?>
        </div>
        <div class="col-xs-12 note_asterisk nop">
            <p><?= AmosDocumenti::t('amosdocumenti', 'I campi') ?> <span class="red">*</span> <?= AmosDocumenti::t('amosdocumenti', 'sono obbligatori') ?>.</p>
        </div>
    </section>
    
    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/documenti/documenti-wizard/introduction', 'id' => $model->id]),
        'cancelUrl' => Yii::$app->session->get(AmosDocumenti::beginCreateNewSessionKey())
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
