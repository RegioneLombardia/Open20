<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

/**
 * @var yii\web\View $this
 * @var \open20\amos\documenti\models\search\DocumentiSearch $model
 * @var yii\widgets\ActiveForm $form
 * @var bool $withoutSearch
 */

use backend\utility\Utility;
use open20\amos\news\AmosNews;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use open20\design\components\bootstrapitalia\ActiveForm;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\DocumentiCategorie;
use kartik\datecontrol\DateControl;

/** @var AmosDocumenti $documentsModule */
$documentsModule = AmosDocumenti::instance();

/** @var DocumentiCategorie $documentiCategorieModel */
$documentiCategorieModel = $documentsModule->createModel('DocumentiCategorie');
$hidePubblicationDate = $documentsModule->hidePubblicationDate;
$hideSearchPubblicationDates = $documentsModule->hideSearchPubblicationDates;
$folders = ArrayHelper::map(\open20\amos\documenti\models\Documenti::find()->andWhere(['is_folder' => 1])->asArray()->all(), 'id', 'titolo');
$folders = ArrayHelper::merge(['' => 'Tutte le cartelle'], $folders);
$categorie = ArrayHelper::map($documentiCategorieModel::find()->asArray()->all(), 'id', 'titolo');
$categorie = ArrayHelper::merge(['' => 'Tutte le categorie'], $categorie);
$urlActionForm = Yii::$app->request->getPathInfo()
    . '?itemId=' . Yii::$app->request->getQueryParams()['itemId']
    . '&version=' . Yii::$app->request->getQueryParams()['version'];

/*PARAMETRI FILTRI*/  
$enableFolderFilter= (isset($documentsModule->enableFolders) && $documentsModule->enableFolders && isset($documentsModule->enableFoldersFilter) && $documentsModule->enableFoldersFilter);
$enableCategoryFilter= $documentsModule->enableCategories;
$enableExtensionFilter=  (isset($documentsModule->enableExtensionFilter) && $documentsModule->enableExtensionFilter);
$enableDateFilter=  (!$hidePubblicationDate && !$hideSearchPubblicationDates);

?>
<div class="search-documents bg-100 rounded my-5 px-3 pt-5 pb-3">
    <?php $form = ActiveForm::begin([
        'action' => $urlActionForm,
        'method' => 'get',
        'options' => [
            'id' => 'documenti_form_' . $model->id,
            'class' => 'form',
            'enctype' => 'multipart/form-data',
        ]
    ]); ?>
    <div class="row">
        <!--TITOLO-->
        <div class="col-sm-6 col-md-4 ">
            <?php
            $attrSearch= 'titolo';
            if(property_exists($model,'genericText')){
                $attrSearch = 'genericText';
             }
             ?>

            <?= $form->field($model, $attrSearch)->textInput([
                'placeholder' => Yii::t('app', 'Argomenti, parole chiave, testo...'),
                'label' => Yii::t('app', 'Cerca')
            ])->label(Yii::t('app', 'Cerca')) ?>
        </div>

        <!--CATEGORIA-->
        <?php if ($enableCategoryFilter): ?>
            <div class="col-sm-6 col-md-4 form-group">
                <?= \open20\design\components\bootstrapitalia\Select::widget([
                    'model' => $model,
                    'attribute' => 'documenti_categorie_id',
                    'label' => Yii::t('app', 'Seleziona una categoria'),
                    'items' => $categorie,
                    'options' => [
                        'id' => 'categorie-filter',
                        'placeholder' => Yii::t('app', 'Seleziona...'),
                        //                    'multiple' => true
                    ]
                ]); ?>
            </div>
        <?php endif; ?>

        <!--CARTELLE-->
        <?php if ($enableFolderFilter): ?>
            <div class="col-sm-6 col-md-4 form-group">
                <?= \open20\design\components\bootstrapitalia\Select::widget([
                    'model' => $model,
                    'attribute' => 'parent_id',
                    'label' => Yii::t('app', 'Seleziona una cartella'),
                    'items' => $folders,
                    'options' => [
                        'id' => 'cartelle-filter',
                        'placeholder' => Yii::t('app', 'Seleziona...'),
                        //                    'multiple' => true
                    ],
                ]); ?>
            </div>
        <?php endif; ?>

        <!--ESTENSIONI FILE-->
        <?php if ($enableExtensionFilter): ?>
            <div class="col-sm-6 col-md-4 form-group">
                <?php
                $extensionsString = $documentsModule->whiteListFilesExtensions;
                $extensions = explode(',', $extensionsString);
                $ext = ['' => 'Tutte le estensioni'];
                foreach ($extensions as $extension) {
                    $ext[$extension] = $extension;
                }
                ?>
                <?= \open20\design\components\bootstrapitalia\Select::widget([
                    'model' => $model,
                    'attribute' => 'extensions',
                    'label' => Yii::t('app', 'Seleziona una estensione'),
                    'items' => $ext,
                    'options' => [
                        'id' => 'extensions-filter',
                        'placeholder' => Yii::t('app', 'Seleziona...'),
                        //                    'multiple' => true,
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        <?php endif; ?>

        <!--DATE PUBBLICAZIONE-->
        <?php if ($enableDateFilter) { ?>
            <div class="col-sm-6  col-md-4">
                <?= $form->field($model, 'data_pubblicazione')->inputCalendar([])->label(AmosDocumenti::t('amosdocumenti', "#data_di_pubblicazione_dal")) ?>
            </div>
            <div class="col-sm-6 col-md-4">
                <?= $form->field($model, 'dataPubblicazioneAl')->inputCalendar([])->label(AmosDocumenti::t('amosdocumenti', "#data_di_pubblicazione_al")) ?>
            </div>
        <?php } ?>

        <!--CTA-->
        <?php
        $clearUrl = '/';
        $explode = explode('?', \Yii::$app->request->getUrl());
        if (!empty($explode)) {
            $clearUrl = $explode[0];
        } ?>

        <div class="col d-flex flex-column flex-sm-row align-items-start align-sm-center justify-content-start justify-content-sm-end">

            <div class="search-btn-container d-flex flex-column flex-sm-row">
                <?= Html::a('<span class="mdi mdi-close"></span> <span class="d-lg-none">' . \Yii::t('app', 'Cancella filtri') . '</span>', $clearUrl, ['class' => 'btn btn-sm px-2 btn-outline-tertiary my-2 mr-1', 'title' => 'Cancella filtri']) ?>
                <?= Html::submitButton('<span class="mdi mdi-filter mr-1"></span>' . AmosNews::tHtml('amosnews', 'Filtra'), ['class' => 'btn btn-sm btn-tertiary my-2', 'title' => \Yii::t('app', 'Applica filtri')]) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
