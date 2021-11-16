<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\attachments\components\CropInput;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\news\AmosNews;
use open20\amos\news\models\News;
use open20\amos\news\models\NewsContentType;
use open20\amos\news\utility\NewsUtility;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use kartik\datecontrol\DateControl;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use open20\agid\person\models\AgidPersonType;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var News $model
 * @var yii\widgets\ActiveForm $form
 * @var amos\sitemanagement\Module|null $siteManagementModule
 */
$dateErrorMessage = AmosNews::t('error', "Controllare data");

$todayDate = date('d-m-Y');
$tomorrowDate = (new DateTime('tomorrow'))->format('d-m-Y');
$moduleNotify = \Yii::$app->getModule('notify');

/** @var AmosNews $newsModule */
$newsModule = AmosNews::instance();

//\open20\amos\layout\assets\SpinnerWaitAsset::register($this);
$js2 = <<<JS
  $(document).ready(function () {
    if($("#news_categorie_id-id option").length == 2){
      $($("#news_categorie_id-id option").parent().parent().parent()).hide();
    }
  });

JS;

$this->registerJs($js2);

$form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'] // important
]);
$customView = Yii::$app->getViewPath() . '/imageField.php';

echo WorkflowTransitionStateDescriptorWidget::widget([
    'form' => $form,
    'model' => $model,
    'workflowId' => News::NEWS_WORKFLOW,
    'classDivIcon' => '',
    'classDivMessage' => 'message',
    'viewWidgetOnNewRecord' => false
]);

$disableStandardWorkflow = $newsModule->disableStandardWorkflow;

// ENABLE AGID FIELDS
$enableAgid = $newsModule->enableAgid;

?>

<div class="news-form ">
    <div class="row">
        <div class="col-xs-12">
            <?php
                $reportModule = \Yii::$app->getModule('report');
                $reportFlagWidget = '';
                if (isset($reportModule) && in_array($model->className(), $reportModule->modelsEnabled)) {
                    $reportFlagWidget = \open20\amos\report\widgets\ReportFlagWidget::widget([
                        'model' => $model,
                    ]);
                }
            ?>
        </div>
        
        <!--nome e categoria-->
        <div class="col-xs-12  section-form">
            <h2 class=" subtitle-form"><?= AmosNews::t('amosnews', 'Nome e categoria') ?></h2>
            <div class="row">
                <div class="col-md-6">
                    <?=
                        $form->field($model, 'titolo')->textInput([
                            'maxlength' => true, 
                            'placeholder' => AmosNews::t('amosnews', '#title_field_plceholder')
                        ])->hint(AmosNews::t('amosnews', '#title'))
                    ?>
                </div>
                <?php if (!$enableAgid): ?>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'sottotitolo')->textInput([
                                'maxlength' => true, 
                                'placeholder' => AmosNews::t('amosnews','#subtitle_field_plceholder')])
                            ->hint(AmosNews::t('amosnews', '#subtitle_field_hint'))
                        ?>
                    </div>
                <?php endif; ?>
                <!-- AGID FIELD -->
                <?php if ($enableAgid): ?>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'news_content_type_id')->widget(\kartik\select2\Select2::className(),[
                                'data' => ArrayHelper::map(NewsContentType::find()->asArray()->all(), 'id', 'name'),
                                'language' => substr(Yii::$app->language, 0, 2),
                                'options' => [
                                    'id' => 'news_content_type_id',
                                    'multiple' => false,
                                    'placeholder' => AmosNews::t('amosnews', 'Seleziona') . ' ...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ])->hint(AmosNews::t('amosnews', '#news_content_type_id'))
                            ->label(AmosNews::t('amosnews', '#news_content_type_id'));
                        ?>
                    </div>
                <?php endif; ?>
                <?php
                    if ($model->isNewRecord && !empty($newsModule->defaultCategory)) {
                        $model->news_categorie_id = $newsModule->defaultCategory;
                    }

                    $newsCategories = NewsUtility::getNewsCategories()->orderBy('titolo')->all();
                    $newsCategoryId = $model->news_categorie_id;
                    if (!$model->news_categorie_id && (count($newsCategories) == 1)) {
                        $newsCategoryId = $newsCategories[0]->id;
                    }
                ?>
                <div class="col-md-6">
                    <?= $form->field($model, 'news_categorie_id')->widget(Select::className(), [
                            'auto_fill' => true,
                            'options' => [
                                'placeholder' => AmosNews::t('amosnews', '#category_field_placeholder'),
                                'id' => 'news_categorie_id-id',
                                'disabled' => false,
                                'value' => $newsCategoryId
                            ],
                            'data' => ArrayHelper::map($newsCategories, 'id', 'titolo'),
                        ])->hint(AmosNews::t('amosnews', '#news_categorie_id'))
                        ->label(AmosNews::t('amosnews', '#news_categorie_id'));
                    ?>
                </div>
            </div>
        </div>

        <!--Immagine principale-->

        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Immagine principale') ?></h2>
            <div class="row">
                <div class="col-md-6">
                    <?=
                        $form->field($model, 'newsImage')->widget(CropInput::classname(), [
                            'jcropOptions' => ['aspectRatio' => '1.7']
                        ])->label(AmosNews::t('amosnews', '#image_field_required')."<span class='required-field'>*(obbligatorio)</span>")->hint(AmosNews::t('amosnews', '#image_field_hint'))
                    ?>
                </div>
            </div>
        </div>

        <!--testo della notizia-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Testo') ?></h2>
            <div class="row">
                <div class="col-md-6">
                    <?=
                        $form->field($model, 'descrizione_breve')->textarea([
                            'maxlength' => 160, 
                            'placeholder' => AmosNews::t('amosnews', '#abstract_field_placeholder')
                        ])->hint(AmosNews::t('amosnews', 'descrizione_breve'))
                    ?>
                </div>
                <div class="col-md-6">
                    <!--TODO cambiare label: Corpo della news -->
                    <?=
                        $form->field($model, 'descrizione')->widget(TextEditorWidget::className(), [
                            'clientOptions' => [
                                'lang' => substr(Yii::$app->language, 0, 2),
                            ],
                        ])->hint(AmosNews::t('amosnews', 'descrizione'));
                    ?>
                </div>
                
            </div>
            
        </div>

         <!-- AGID FIELD, date -->
        <?php if ($enableAgid): ?>
            
            <div class="col-xs-12 section-form">
                <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Date') ?></h2>
                <div class="row">
                    <div class="col-md-6 ">
                        <!-- AGID FIELD -->
                        <?php 
                            if( $model->isNewRecord ){
                                $model->date_news = date("Y-m-d");
                            }
                        ?>
                        <?= 
                            $form->field($model, 'date_news')->widget(DateControl::classname(), [
                                'value' => $model->isNewRecord ? date("Y-m-d") : ''
                            ])
                                ->hint(AmosNews::t('amosnews', 'date_news'))
                                ->label(AmosNews::t('amosnews', 'date_news')); 
                        ?>
                    </div>
                    <div class="col-md-6">
                        <!-- AGID FIELD -->
                        <?= 
                            $form->field($model, 'news_expiration_date')->widget(DateControl::classname(), [])
                                ->hint(AmosNews::t('amosnews', 'news_expiration_date'))
                                ->label(AmosNews::t('amosnews', 'news_expiration_date')); 
                        ?>
                    </div>
                    
                </div>
            </div>
        <?php endif; ?>

        <!--documenti e allegati-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Documenti e allegati') ?></h2>
            <div class="row">
                <!-- AGID FIELD -->
                <?php if ($enableAgid): ?>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'news_documento_id')->widget(\kartik\select2\Select2::className(),[
                                'data' => ArrayHelper::map(\open20\amos\documenti\models\Documenti::find()->andwhere(['status' => 'DocumentiWorkflow/VALIDATO'])->andWhere(['deleted_at' => null])->all(), 'id', 'titolo'),
                                'language' => substr(Yii::$app->language, 0, 2),
                                'options' => [
                                    'id' => 'news_documento_id',
                                    'multiple' => false,
                                    'placeholder' => AmosNews::t('amosnews', 'Seleziona') . ' ...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ])->hint(AmosNews::t('amosnews', '#news_documento_id'));
                        ?>
                    </div>
                <?php endif; ?>

                <div class="col-md-6">
                    <?=
                        $form->field($model, 'attachments')->widget(AttachmentsInput::classname(),[
                            'options' => [// Options of the Kartik's FileInput widget
                                'multiple' => true, // If you want to allow multiple upload, default to false
                            ],
                            'pluginOptions' => [// Plugin options of the Kartik's FileInput widget
                                'maxFileCount' => 100, // Client max files
                                'showPreview' => false
                            ]
                        ])->hint(AmosNews::t('amosnews','#attachments_field_hint'))->label(AmosNews::t('amosnews', '#attachments_title'));
                    ?>
                    <?=
                        AttachmentsList::widget([
                            'model' => $model,
                            'attribute' => 'attachments'
                        ])
                    ?>    
                </div>
            </div>
        </div>

       

        <!--referenti-->

        <!-- AGID FIELD -->
        <?php if ($enableAgid): ?>
            <div class="col-xs-12 section-form">
                <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Referenti') ?></h2>
                <div class="row">
                    <div class="col-md-6">
                        <!-- AGID FIELD -->
                        <?=
                            $form->field($model, 'edited_by_agid_organizational_unit_id')->widget(\kartik\select2\Select2::className(),[
                                'data' => ArrayHelper::map(AgidOrganizationalUnit::find()->asArray()->all(), 'id', 'name'),
                                'language' => substr(Yii::$app->language, 0, 2),
                                'options' => [
                                    'id' => 'edited_by_agid_organizational_unit_id',
                                    'multiple' => false,
                                    'placeholder' => AmosNews::t('amosnews', 'Seleziona') . ' ...',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ])->hint(AmosNews::t('amosnews', '#edited_by_agid_organizational_unit_id'));
                        ?>
                    </div>
                    <div class="col-md-6">
                        <!-- AGID FIELD -->
                        <?php 
                            foreach ($model->newsAgidPersonMm as $key => $value) {
                                $news_agid_person_mm[] = $value->agid_person_id ;
                            }
                        ?>
                        <?= 
                            $form->field($model, 'news_agid_person_mm[]')->widget(\kartik\select2\Select2::className(),[
                                'data' => ArrayHelper::map(\open20\agid\person\models\AgidPerson::find()
                                ->select(["id, name, surname, CONCAT(surname, ' ', name) AS surnameName"])
                                ->andWhere(['agid_person_type_id' => AgidPersonType::find()->andWhere(['name' => "Politica"])->one()->id ])
                                ->andWhere(['deleted_at' => null])
                                ->all(), 'id', 'surnameName'),
                                'options' => [
                                    'placeholder' => Yii::t('amosnews', 'Seleziona...'),
                                    'multiple' => true,
                                    'value' => isset($news_agid_person_mm) ? $news_agid_person_mm : null,
                                ],
                            ])->label(Yii::t('amosnews', 'Persone'))
                        ?>
                    </div>
                </div>
                
            </div>
        <?php endif; ?>


        <?php if ($enableAgid): ?>
            <!-- AGID FIELD, contenuti multimediali -->
            <?php
            if (!is_null($siteManagementModule)) {
                $siteManagementModule::setExternalPreviousSessionKeys(Url::current(), $model->getGrammar()->getModelSingularLabel() . ' ' . $model->getTitle());
            }
            ?>
            <div class="col-xs-12">
                <div class="row">
                    
                            <div class="col-md-6 ">
                                <?php
                                    if (!$model->isNewRecord) {
                                        $max             = \amos\sitemanagement\models\SiteManagementSliderElem::find()->andWhere(['slider_id' => $model->image_site_management_slider_id])->max("site_management_slider_elem.order");
                                        $min             = \amos\sitemanagement\models\SiteManagementSliderElem::find()->andWhere(['slider_id' => $model->image_site_management_slider_id])->min("site_management_slider_elem.order");
                                        $newsModel = $model;
                                ?>
                                        <h3><?= AmosNews::t('amosnews', 'Contenuti multimediali') ?></h3>
                                        <?=
                                            Html::a(AmosNews::t('amosnews', 'Aggiungi immagine'),
                                                [
                                                    '/sitemanagement/site-management-slider-elem/create', 
                                                    'id' => $slider_image->id, 
                                                    'slider_type' => \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_IMG,
                                                    "image" , 
                                                    'urlRedirect' => urlencode('/news/news/update?id='.$newsModel->id.'#tab-immagine'), 
                                                    'useCrop' => true, 
                                                    'cropRatio' => 1.7
                                                ],
                                                [
                                                    'class' => 'btn btn-primary', 
                                                    'data-confirm' => AmosNews::t('amosnews', 'Stai per lasciare la pagina, assicurarsi di aver salvato dati. Proseguire?')
                                                ]
                                            );
                                        ?>
                                        <?php
                                            $gridColumns = [
                                                'order',
                                                'type' => [
                                                    'attribute' => 'type',
                                                    'value' => function ($model) {
                                                        return $model->getLabelType();
                                                    },
                                                    'enableSorting' =>false
                                                ],
                                                'title' => [
                                                    'attribute' => 'title',
                                                    'enableSorting' =>false
                                                ],
                                                [
                                                    'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                                                    'controller' => 'site-management-slider-elem',
                                                    'template' => '{update}{delete}',
                                                    'buttons' => [
                                                        'update' => function ($url, $model) use ($newsModel) {
                                                            return Html::a(\open20\amos\core\icons\AmosIcons::show('edit'),
                                                                [
                                                                    '/sitemanagement/site-management-slider-elem/update',
                                                                    'id' => $model->id,
                                                                    'urlRedirect' => urlencode('/news/news/update?id='.$newsModel->id.'#tab-immagine')
                                                                ],
                                                                [
                                                                    'class' => 'btn btn-primary'
                                                                ]
                                                            );
                                                        },
                                                        'view' => function ($url, $model) use ($newsModel) {
                                                            return Html::a(\open20\amos\core\icons\AmosIcons::show('file'),
                                                                [
                                                                    '/sitemanagement/site-management-slider-elem/view', 
                                                                    'id' => $model->id, 
                                                                    'urlRedirect' => urlencode('/news/news/update?id='.$newsModel->id.'#tab-immagine')
                                                                ],
                                                                [
                                                                    'class' => 'btn btn-primary'
                                                                ]
                                                            );
                                                        },
                                                        'delete' => function ($url, $model) use ($newsModel) {
                                                            return Html::a(\open20\amos\core\icons\AmosIcons::show('delete'),
                                                                [
                                                                    '/sitemanagement/site-management-slider-elem/delete', 
                                                                    'id' => $model->id, 'urlRedirect' => urlencode('/news/news/update?id='.$newsModel->id.'#tab-immagine')],
                                                                [
                                                                    'class' => 'btn btn-danger-inverse'
                                                                ]
                                                            );
                                                        }
                                                    ]
                                                ]
                                            ];

                                            if($readOnly) {
                                                array_pop($gridColumns);
                                            }
                                            echo \open20\amos\core\views\AmosGridView::widget([
                                                'dataProvider' => $dataProviderSliderElemImage,
                                                'columns' => $gridColumns
                                            ]);
                                        ?>
                                    <?php
                                        } /*else {
                                            echo "<p>".AmosNews::t('amossitemanagement',
                                                "E' necessario salvare per poter inserire degli lementi allo slider.")."</p>";
                                        }*/
                                    ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                    if (!$model->isNewRecord) {
                                ?>
                                        <h3><?= AmosNews::t('amosnews', 'Video notizia') ?></h3>
                                        <?=
                                            Html::a(\Yii::t('amosnews', 'Aggiungi video'),
                                                [
                                                    '/sitemanagement/site-management-slider-elem/create', 
                                                    'id' => $slider_video->id, 
                                                    'slider_type' => \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_VIDEO, 
                                                    
                                                    'urlRedirect' => urlencode('/news/news/update?id='.$newsModel->id.'#tab-video'), 

                                                    'useCrop' => true, 
                                                    'cropRatio' => 1.7
                                                ],
                                                [
                                                    'class' => 'btn btn-primary', 
                                                    'data-confirm' => AmosNews::t('amosnews', 'Stai per lasciare la pagina, assicurarsi di aver salvato dati. Proseguire?')
                                                ]
                                            );
                                        ?>
                                        <?php
                                            $gridColumns1 = [
                                                'order',
                                                'type' => [
                                                    'attribute' => 'type',
                                                    'value' => function ($model) {
                                                        return $model->getLabelType();
                                                    },
                                                    'enableSorting' =>false
                                                ],
                                                'title' => [
                                                    'attribute' => 'title',
                                                    'enableSorting' =>false
                                                ],
                                                [
                                                    'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                                                    'controller' => 'site-management-slider-elem',
                                                    'template' => '{update}{delete}',
                                                    'buttons' => [
                                                        'update' => function ($url, $model) use ($newsModel) {
                                                            return Html::a(\open20\amos\core\icons\AmosIcons::show('edit'),
                                                                [
                                                                    '/sitemanagement/site-management-slider-elem/update',
                                                                    'id' => $model->id,
                                                                    'urlRedirect' => urlencode('/news/news/update?id='.$newsModel->id.'#tab-video')
                                                                ],
                                                                [
                                                                    'class' => 'btn btn-primary'
                                                                ]
                                                            );
                                                        },
                                                        'view' => function ($url, $model) use ($newsModel) {
                                                            return Html::a(\open20\amos\core\icons\AmosIcons::show('file'),
                                                                [
                                                                    '/sitemanagement/site-management-slider-elem/view', 
                                                                    'id' => $model->id, 
                                                                    'urlRedirect' => urlencode('/news/news/update?id='.$newsModel->id.'#tab-video')
                                                                ],
                                                                [
                                                                    'class' => 'btn btn-primary'
                                                                ]
                                                            );
                                                        },
                                                        'delete' => function ($url, $model) use ($newsModel) {
                                                            return Html::a(\open20\amos\core\icons\AmosIcons::show('delete'),
                                                                [
                                                                    '/sitemanagement/site-management-slider-elem/delete', 
                                                                    'id' => $model->id, 'urlRedirect' => urlencode('/news/news/update?id='.$newsModel->id.'#tab-video')],
                                                                [
                                                                    'class' => 'btn btn-danger-inverse'
                                                                ]
                                                            );
                                                        }
                                                    ]
                                                ]
                                            ];

                                            if($readOnly) {
                                                array_pop($gridColumns);
                                            }
                                            echo \open20\amos\core\views\AmosGridView::widget([
                                                'dataProvider' => $dataProviderSliderElemVideo,
                                                'columns' => $gridColumns1
                                            ]);
                                        ?>

                                    <?php
                                        } /*else {
                                            echo "<p>".AmosNews::t('amossitemanagement',
                                                "E' necessario salvare per poter inserire degli lementi allo slider.")."</p>";
                                        }*/
                                    ?>
                            </div>
                </div>
            </div>

            <div class="col-xs-12 section-form">
                <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Contenuti correlati') ?></h2>
                <div class="row">
                  
                    <div class="col-md-6">
                        <!-- NEWS AGID FIELD -->
                        <?php 
                            foreach ($model->newsRelatedNewsMm as $key => $value) {
                                $news_related_news_mm[] = $value->related_news_id ;
                            }
                        ?>
                        <?=
                            $form->field($model, 'news_related_news_mm[]')->widget(\kartik\select2\Select2::className(),[
                                'data' => ArrayHelper::map(\open20\amos\news\models\News::find()->andwhere(['status' => News::NEWS_WORKFLOW_STATUS_VALIDATO])->andWhere(['deleted_at' => null])->andWhere(['!=','id', $model->id])->all(), 'id', 'titolo'),
                                'language' => substr(Yii::$app->language, 0, 2),
                                'options' => [
                                    'id' => 'news_related_news_mm',
                                    'multiple' => true,
                                    'placeholder' => AmosNews::t('amosnews', 'Seleziona') . ' ...',
                                    'value' => isset($news_related_news_mm) ? $news_related_news_mm : null,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ])->hint(AmosNews::t('amosnews', 'news_related_news_mm'));
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php if ($newsModule->enableAgidNewsRelatedDocumenti == true): ?>
                        <?php 
                            foreach ($model->newsRelatedDocumentiMm as $key => $value) {
                                $news_related_documenti_mm[] = $value->related_documenti_id ;
                            }
                        ?>
                        <?=
                            $form->field($model, 'news_related_documenti_mm[]')->widget(\kartik\select2\Select2::className(),[
                                'data' => ArrayHelper::map(\open20\amos\documenti\models\Documenti::find()->andwhere(['status' => 'DocumentiWorkflow/VALIDATO'])->andWhere(['deleted_at' => null])->all(), 'id', 'titolo'),
                                'language' => substr(Yii::$app->language, 0, 2),
                                'options' => [
                                    'id' => 'news_related_documenti_mm',
                                    'multiple' => true,
                                    'placeholder' => AmosNews::t('amosnews', 'Seleziona') . ' ...',
                                    'value' => isset($news_related_documenti_mm) ? $news_related_documenti_mm : null,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ])->hint(AmosNews::t('amosnews', 'news_related_documenti_mm'));
                        ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <?php if ($newsModule->enableAgidNewsRelatedAgidService == true): ?>
                        <?php 
                            foreach ($model->newsRelatedAgidServiceMm as $key => $value) {
                                $news_related_agid_service_mm[] = $value->related_agid_service_id ;
                            }
                        ?>
                        <?=
                            $form->field($model, 'news_related_agid_service_mm[]')->widget(\kartik\select2\Select2::className(),[
                                'data' => ArrayHelper::map(\open20\agid\service\models\AgidService::find()->andWhere(['status' => 'AgidServiceWorkflow/VALIDATED'])->andWhere(['deleted_at' => null])->all(), 'id', 'name'),
                                'language' => substr(Yii::$app->language, 0, 2),
                                'options' => [
                                    'id' => 'news_related_agid_service_mm',
                                    'multiple' => true,
                                    'placeholder' => AmosNews::t('amosnews', 'Seleziona') . ' ...',
                                    'value' => isset($news_related_agid_service_mm) ? $news_related_agid_service_mm : null,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ]
                            ])->hint(AmosNews::t('amosnews', 'news_related_agid_service_mm'));
                        ?>
                        <?php endif; ?>
                    </div>
                  
                </div>
            </div>
        <?php endif; ?>

    </div>


    <div class="row">

        <?php
        $showReceiverSection = false;

            $moduleCwh = \Yii::$app->getModule('cwh');
            isset($moduleCwh) ? $showReceiverSection = true : null;
            isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;

        $pubblicatedForCommunity = false;
        if (!$model->isNewRecord && isset($moduleCwh)) {
            if (!empty($model->validatori)) {
                foreach ($model->validatori as $validatore) {
                    $network = \open20\amos\cwh\utility\CwhUtil::getNetworkFromId($validatore);
                    if ($network instanceof \open20\amos\community\models\Community) {
                        $pubblicatedForCommunity = true;
                    }
                }
            }
        }

        $moduleTag = \Yii::$app->getModule('tag');
        //     isset($moduleTag) ? $showReceiverSection = true : null;

        if (isset($moduleTag)) : ?>
            <?php
            $tagTitle = ($enableAgid ? AmosNews::t('amosnews', '#tag') : AmosNews::t('amosnews', '#tags_title'));
            ?>
            <div class="col-xs-12">
                <?=
                Html::tag('h2', $tagTitle, ['class' => 'subtitle-form'])
                ?>
                <div class="col-xs-12 receiver-section">
                    <?php
                    echo \open20\amos\cwh\widgets\DestinatariPlusTagWidget::widget([
                        'model' => $model,
                        'moduleCwh' => $moduleCwh,
                        'scope' => $scope
                    ]);
                    ?>
                </div>

                <?php
                $publish_enabled = \Yii::$app->user->can('NEWS_PUBLISHER_FRONTEND')
                    || !empty(Yii::$app->getModule('news')->params['publication_always_enabled']);

                $publish_enabled = $publish_enabled
                    ? $publish_enabled
                    : \Yii::$app->user->can('NEWS_PUBLISHER_FRONTEND')
                    && empty($scope)
                    && !$pubblicatedForCommunity;

                if ($publish_enabled) {
                    if (Yii::$app->getModule('news')->params['site_publish_enabled'] || Yii::$app->getModule('news')->params['site_featured_enabled']) {
                        ?>
                        <div class="col-xs-12 receiver-section">
                            <div class="row">
                                <?php if (Yii::$app->getModule('news')->params['site_publish_enabled']) { ?>

                                    <h3 class="subtitle-section-form"><?= AmosNews::t('amosnews', "Pubblication on the portal mode") ?>
                                        <em>(<?= AmosNews::t('amosnews', "Choose if you want to publish the news also on the portal") ?>
                                            )</em>
                                    </h3>
                                    <?php
                                    $primoPiano = '<div class="col-md-6 col-xs-12">'
                                        . $form->field($model, 'primo_piano')->widget(Select::className(),
                                            [
                                                'auto_fill' => true,
                                                'data' => [
                                                    '0' => AmosNews::t('amosnews', 'No'),
                                                    '1' => AmosNews::t('amosnews', 'Si')
                                                ],
                                                'options' => [
                                                    'value' => (isset($enableAgid) && true == $enableAgid) ? 1 : null,
                                                    'prompt' => AmosNews::t('amosnews', 'Seleziona'),
                                                    'disabled' => false,
                                                    'onchange' => "
                if($(this).val() == 1) $('#news-in_evidenza').prop('disabled', false);
                if($(this).val() == 0) {
                    $('#news-in_evidenza').prop('disabled', true);
                    $('#news-in_evidenza').val(0);
                }"
                                                ],
                                            ]) .
                                        '</div>';
                                    echo $primoPiano;
                                }

                                if (Yii::$app->getModule('news')->params['site_featured_enabled']) {
                                    $inEvidenza = '<div class="col-md-6 col-xs-12">'
                                        . $form->field($model, 'in_evidenza')->widget(Select::className(),
                                            [
                                                'auto_fill' => true,
                                                'data' => [
                                                    '0' => AmosNews::t('amosnews', 'No'),
                                                    '1' => AmosNews::t('amosnews', 'Si')
                                                ],
                                                'options' => [
                                                    'value' => (isset($enableAgid) && true == $enableAgid) ? 1 : null,
                                                    'prompt' => AmosNews::t('amosnews', 'Seleziona'),
                                                    // 'disabled' => ($model->primo_piano == 1 ? false : true)
                                                    'disabled' =>  (isset($enableAgid) && true == $enableAgid) ? false : ($model->primo_piano == 1 ? false : true)
                                                ]
                                            ])
                                        . '</div>';
                                    echo $inEvidenza;
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }

                ?>
            </div>

        <?php endif; ?>

        <?php if (\Yii::$app->getModule('correlations')) { ?>
            <?=
            open2\amos\correlations\widget\ManageCorrelationsButtonWidget::widget([
                'model' => $model
            ]);
            ?>
            <?php
        }
        ?>
        <div class="col-xs-12 note_asterisk">
            <span><?= AmosNews::t('amosnews', '#required_field') ?></span>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12">
            <?php
            $moduleNews = \Yii::$app->getModule(AmosNews::getModuleName());

            if ($moduleNews->hidePubblicationDate == false) {
                $publicationDate = Html::tag(
                        'div',
                        $form->field($model, 'data_pubblicazione')->widget(DateControl::className(), [
                            'type' => DateControl::FORMAT_DATE
                        ])
                            ->hint(AmosNews::t('amosnews', '#start_publication_date_hint')),
                        ['class' => 'col-md-4 col-xs-12']
                    )
                    . Html::tag(
                        'div',
                        $form->field($model, 'data_rimozione')->widget(DateControl::className(), [
                            'type' => DateControl::FORMAT_DATE
                        ])
                            ->hint(AmosNews::t('amosnews', '#end_publication_date_hint')),
                        ['class' => 'col-md-4 col-xs-12']
                    );
            } else {
                $publicationDate = '';
            }

            if ($model->isNewRecord) { //default enable comment
                $model->comments_enabled = $moduleNews->defaultEnableComments;
            }

            $enableComments = Html::tag(
                'div',
                $form->field($model, 'comments_enabled')->inline()->radioList(
                    [
                        '1' => AmosNews::t('amosnews', '#comments_ok'),
                        '0' => AmosNews::t('amosnews', '#comments_no')
                    ],
                    ['class' => 'comment-choice']
                ),
                ['class' => 'col-md-4 col-xs-12']
            );
            ?>
            <?php
            $contentLanguage = '';
            if ($moduleNotify && !empty($moduleNotify->enableNotificationContentLanguage) && $moduleNotify->enableNotificationContentLanguage) { ?>
                <?php
                $contentLanguage = "<div class=\"col-xs-6 nop\">" . \open20\amos\notificationmanager\widgets\NotifyContentLanguageWidget::widget(['model' => $model]) . "</div>"
                ?>
            <?php } ?>

            <?=
            AccordionWidget::widget([
                'items' => [
                    [
                        'header' => AmosNews::t('amosnews', '#settings_optional'),
                        'content' => $publicationDate . $enableComments . $contentLanguage . '<div class="clearfix"></div>',
                    ]
                ],
                'headerOptions' => ['tag' => 'h2'],
                'clientOptions' => [
                    'collapsible' => true,
                    'active' => 'false',
                    'icons' => [
                        'header' => 'ui-icon-amos am am-plus-square',
                        'activeHeader' => 'ui-icon-amos am am-minus-square',
                    ]
                ],
            ]);
            ?>

            <?php if (\Yii::$app->getModule('seo')) : ?>
                <?=
                AccordionWidget::widget([
                    'items' => [
                        [
                            'header' => AmosNews::t('amosnews', '#settings_seo_title'),
                            'content' => \open20\amos\seo\widgets\SeoWidget::widget([
                                'contentModel' => $model,
                            ]),
                        ]
                    ],
                    'headerOptions' => ['tag' => 'h2'],
                    'options' => Yii::$app->user->can('ADMIN') ? [] : ['style' => 'display:none;'],
                    'clientOptions' => [
                        'collapsible' => true,
                        'active' => 'false',
                        'icons' => [
                            'header' => 'ui-icon-amos am am-plus-square',
                            'activeHeader' => 'ui-icon-amos am am-minus-square',
                        ]
                    ],
                ]);
                ?>
            <?php endif; ?>
        </div>

        <?php
        $config = [
            'model' => $model,
            'urlClose' => Yii::$app->session->get('previousUrl'),
            'buttonClassSave' => 'btn btn-workflow',
        ];

        $statusToRenderToHide = $model->getStatusToRenderToHide();

        $draftButtons = [];
        if ($disableStandardWorkflow == false) {
            $draftButtons = [
                News::NEWS_WORKFLOW_STATUS_DAVALIDARE => [
                    'button' => Html::submitButton(AmosNews::t('amosnews', 'Salva'), ['class' => 'btn btn-workflow']),
                    'description' => AmosNews::t('amosnews', 'le modifiche e mantieni la notizia in "richiesta di pubblicazione"')
                ],
                News::NEWS_WORKFLOW_STATUS_VALIDATO => [
                    'button' => \open20\amos\core\helpers\Html::submitButton(AmosNews::t('amosnews', 'Salva'),
                        ['class' => 'btn btn-workflow']),
                    'description' => AmosNews::t('amosnews', 'le modifiche e mantieni la notizia "pubblicata"'),
                ],
                'default' => [
                    'button' => Html::submitButton(AmosNews::t('amosnews', 'Salva in bozza'),
                        ['class' => 'btn btn-workflow']),
                    'description' => AmosNews::t('amosnews', 'potrai richiedere la pubblicazione in seguito'),
                ]
            ];
        }

        echo WorkflowTransitionButtonsWidget::widget([
            'form' => $form,
            'model' => $model,
            'workflowId' => News::NEWS_WORKFLOW,
            'viewWidgetOnNewRecord' => true,
            //'closeSaveButtonWidget' => CloseSaveButtonWidget::widget($config),
            'closeButton' => Html::a(AmosNews::t('amosnews', 'Annulla'), Yii::$app->session->get('previousUrl'),
                ['class' => 'btn btn-secondary']),
            'initialStatusName' => "BOZZA",
            'initialStatus' => News::NEWS_WORKFLOW_STATUS_BOZZA,
            'statusToRender' => $statusToRenderToHide['statusToRender'],
            //POII-1147 gli utenti validatore/facilitatore o ADMIN possono sempre salvare la news => parametro a false
            //altrimenti se stato VALIDATO => pulsante salva nascosto
            'hideSaveDraftStatus' => $statusToRenderToHide['hideDraftStatus'],
            'draftButtons' => $draftButtons
        ]);
        ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
