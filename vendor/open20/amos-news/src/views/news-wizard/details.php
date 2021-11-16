<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news-wizard
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\news\AmosNews;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use yii\redactor\widgets\Redactor;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\News $model
 * @var yii\widgets\ActiveForm $form
 */

$this->title = AmosNews::t('amosnews', '#news_wizard_page_title');

?>

<div class="news-wizard-details">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'news-wizard-form',
            'class' => 'form',
            'enctype' => 'multipart/form-data', // To load images
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>
    <?= $form->errorSummary($model, ['class' => 'alert-danger alert fade in', 'role' => 'alert']); ?>
    <section>
        <div class="row">
            <div class="col-lg-8 col-sm-8">
                <?= $form->field($model, 'titolo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'sottotitolo')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'descrizione_breve')->textarea(['maxlength' => true, 'rows' => 3]) ?>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="col-xs-12 pull-right">
                    <?= $form->field($model,
                        'newsImage')->widget(AttachmentsInput::classname(), [
                        'options' => [ // Options of the Kartik's FileInput widget
                            'multiple' => false, // If you want to allow multiple upload, default to false
                            'accept' => "image/*"
                        ],
                        'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                            'maxFileCount' => 1,
                            'showRemove' => false,// Client max files,
                            'indicatorNew' => false,
                            'allowedPreviewTypes' => ['image'],
                            'previewFileIconSettings' => false,
                            'overwriteInitial' => false,
                            'layoutTemplates' => false
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'descrizione')->widget(Redactor::className(), [
                    'clientOptions' => [
                        'placeholder' => AmosNews::t('amosnews', '#news_text_placeholder'),
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
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'news_categorie_id')->widget(Select::className(), [
                    'auto_fill' => true,
                    'options' => [
                        'placeholder' => AmosNews::t('amosnews', 'Digita il nome della categoria'),
                        'id' => 'news_categorie_id-id',
                        'disabled' => false
                    ],
                    'data' => ArrayHelper::map(\open20\amos\news\utility\NewsUtility::getNewsCategories()->orderBy('titolo')->all(), 'id', 'titolo')
                ]); ?>
            </div>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'comments_enabled')->widget(Select::className(), [
                    'auto_fill' => true,
                    'data' => [
                        '0' => AmosNews::t('amosnews', 'No'),
                        '1' => AmosNews::t('amosnews', 'Si')
                    ],
                    'options' => [
                        'prompt' => AmosNews::t('amosnews', 'Seleziona'),
                        'disabled' => false
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <?php
            if (Yii::$app->getModule('news')->params['site_publish_enabled']) { ?>
                <div class="col-lg-6 col-sm-6">
                    <?= $form->field($model, 'primo_piano')->widget(Select::className(), [
                        'auto_fill' => true,
                        'data' => [
                            '0' => AmosNews::t('amosnews', 'No'),
                            '1' => AmosNews::t('amosnews', 'Si')
                        ],
                        'options' => [
                            'prompt' => AmosNews::t('amosnews', 'Seleziona'),
                            'disabled' => false,
                            'onchange' => "
                        if($(this).val() == 1) $('#news-in_evidenza').prop('disabled', false);
                        if($(this).val() == 0) {
                            $('#news-in_evidenza').prop('disabled', true);
                            $('#news-in_evidenza').val(0);
                        }"
                        ],
                    ]) ?>
                </div>
            <?php }
            if (Yii::$app->getModule('news')->params['site_featured_enabled']) { ?>
                <div class="col-lg-6 col-sm-6">
                    <?= $form->field($model, 'in_evidenza')->widget(Select::className(), [
                        'auto_fill' => true,
                        'data' => [
                            '0' => AmosNews::t('amosnews', 'No'),
                            '1' => AmosNews::t('amosnews', 'Si')
                        ],
                        'options' => [
                            'prompt' => AmosNews::t('amosnews', 'Seleziona'),
                            'disabled' => true
                        ]
                    ]) ?>
                </div>
            <?php } ?>
            <?php
            $moduleNews = \Yii::$app->getModule(AmosNews::getModuleName());
            if($moduleNews->hidePubblicationDate == false){?>
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
            <?php }?>
        </div>
        <div class="col-xs-12 note_asterisk nop">
            <p><?= AmosNews::t('amosnews', 'I campi') ?> <span class="red">*</span> <?= AmosNews::t('amosnews', 'sono obbligatori') ?>.</p>
        </div>
    </section>
    
    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/news/news-wizard/introduction', 'id' => $model->id]),
        'cancelUrl' => Yii::$app->session->get(AmosNews::beginCreateNewSessionKey())
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
