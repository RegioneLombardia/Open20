<?php

use open20\amos\core\forms\editors\Select;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\controllers\DocumentiController;
use open20\amos\documenti\models\DocumentiCategorie;
use open20\amos\tag\AmosTag;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\base\DynamicModel;
use yii\helpers\Url;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);

$moduleDocumenti = \Yii::$app->getModule(AmosDocumenti::getModuleName());

$hidePubblicationDate = $moduleDocumenti->hidePubblicationDate;
$hideSearchPubblicationDates = $moduleDocumenti->hideSearchPubblicationDates;
$hideSearchPubblicationFromTo = $moduleDocumenti->hideSearchPubblicationFromTo;
$enableCategories = $moduleDocumenti->enableCategories;
$enableExtensionFilter = $moduleDocumenti->enableExtensionFilter;

$categorie = ArrayHelper::map(DocumentiCategorie::find()->asArray()->all(), 'id', 'titolo');
$categorie = ArrayHelper::merge(['' => 'Tutte le categorie'], $categorie);

$url = Url::to(['/documenti/documenti-ajax/file-explorer']);

$model = new DynamicModel(['descrizione','data_pubblicazione_from','data_pubblicazione_to','extensions','ricerca_sottocartelle','documenti_categorie_id']);
foreach($filters as $attribute=>$val){
    $model->$attribute = $val;
}

?>

<div class="documenti-search element-to-toggle" data-toggle-element="form-search">
    <div class="pb-4"><p class="h3"></p></div>

    <?php $form = ActiveForm::begin([       
        'method' => 'post',
    ]);
    
    ?>
    
    <div class="row">

        <div class="col-sm-6 col-lg-4">
            <?= $form->field($model, 'descrizione')->textInput(['maxlength' => true, 'placeholder' => AmosDocumenti::t('amosdocumenti', "Ricerca per titolo, sottotitolo e testo")])->label(AmosDocumenti::t('amosdocumenti', "Cerca")) ?>
        </div>
        
        <!--CATEGORIA-->
        <?php if ($enableCategories && !$params['hideCategory']): ?>
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

        <?php if ($params['advancedSearch'] && !$hidePubblicationDate): ?>
            <?php if (!$hideSearchPubblicationDates) { ?>
                <div class="col-sm-6 col-lg-4">
                    <?= $form->field($model, 'data_pubblicazione_from')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE,  
                        'options' => [

                        ],

                    ])->label(AmosDocumenti::t('amosdocumenti', "#data_di_pubblicazione_dal")) ?>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <?= $form->field($model, 'data_pubblicazione_to')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE,
                        'options' => [

                        ]
                    ])->label(AmosDocumenti::t('amosdocumenti', "#data_di_pubblicazione_al")) ?>
                </div>
            <?php } ?>
        <?php endif; ?>

        <?php if ($params['advancedSearch'] && $enableExtensionFilter): ?>
            <div class="col-sm-6 col-lg-4">
                <?php $extensionsString = $moduleDocumenti->whiteListFilesExtensions;
                $extensions = explode(',', $extensionsString);
                $ext = [];
                foreach ($extensions as $extension) {
                    $ext[$extension] = $extension;
                }
                ?>
                <?= $form->field($model, 'extensions')->widget(Select::className(), [

                    'data' => $ext,
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => true,
                        'placeholder' => AmosDocumenti::t('amosdocumenti', 'Search by extension'),
                        'class'=>'form-select2',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(AmosDocumenti::t('amosdocumenti', "Estensione")) ?>
            </div>
        <?php endif; ?>


        <?php if ($params['advancedSearch']): ?>
            <div class="col-sm-6 col-lg-4">      
                <?= $form->field($model, 'ricerca_sottocartelle')->widget(Select::className(), [

                        'data' => [0=>'NO',1=>'SI'],
                        'language' => substr(Yii::$app->language, 0, 2),
                        'options' => [
                            'multiple' => false,                  
                            'class'=>'form-select2',
                        ],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]) ?>
            </div>
        <?php endif; ?>
        
        <div class="col d-flex flex-column flex-sm-row align-items-start align-sm-center justify-content-start justify-content-sm-end">

            <div class="search-btn-container d-flex flex-column flex-sm-row">
                <?= Html::a('<span class="mdi mdi-close"></span> <span class="d-lg-none">' . AmosDocumenti::t('amosdocumenti', 'Cancella filtri') . '</span>', '#', ['class' => 'btn btn-sm px-2 btn-outline-tertiary my-2 mr-1', 'title' => 'Cancella filtri','onclick'=>"
                        event.preventDefault();

                        var url = '$url'; 
                        var id = '{$folder->id}';

                        sendData(url,id);
                    "]) 
                ?>
                <?= Html::submitButton('<span class="mdi mdi-filter mr-1"></span>' . AmosDocumenti::tHtml('amosdocumenti', 'Filtra'), ['class' => 'btn btn-sm btn-tertiary my-2', 'title' => AmosDocumenti::t('amosdocumenti', 'Applica filtri'),'onclick'=>"
                        event.preventDefault();
                        var form = $(this).closest('form')[0];
                        var formData = new FormData(form);
                        var url = '$url'; 
                        var id = '{$folder->id}';
                        console.log(formData);
                        sendData(url,id,formData);
                    "]) ?>
            </div>

        </div>
    
    </div>

    <?php ActiveForm::end(); ?>

</div>