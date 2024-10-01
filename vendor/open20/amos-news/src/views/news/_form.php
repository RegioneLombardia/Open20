<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news
 * @category   CategoryName
 */

use amos\sitemanagement\models\SiteManagementSliderElem;
use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\attachments\components\CropInput;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\news\AmosNews;
use open20\amos\news\models\News;
use open20\amos\news\models\NewsContentType;
use open20\amos\news\utility\NewsUtility;
use open20\amos\core\forms\editors\maps\PlaceWidget;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use open20\amos\core\icons\AmosIcons;
use open20\amos\attachments\FileModule;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use kartik\widgets\SwitchInput;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use open20\agid\person\models\AgidPersonType;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use open20\amos\comments\AmosComments;

/**
 *
 * @var yii\web\View $this
 * @var News $model
 * @var yii\widgets\ActiveForm $form
 * @var amos\sitemanagement\Module|null $siteManagementModule
 * @var \open20\amos\news\models\NewsRelatedEventMm $newsRelatedEvent
 */

$dateErrorMessage = AmosNews::t('error', "Controllare data");
/** @var AmosNews $newsModule */
$newsModule = \Yii::$app->getModule(AmosNews::getModuleName());
$enableOtherNewsCategories = $newsModule->enableOtherNewsCategories;
$enablePrimoPianoOnCommunity = $newsModule->enablePrimoPianoOnCommunity;

$todayDate = date('d-m-Y');
$tomorrowDate = (new DateTime('tomorrow'))->format('d-m-Y');
$moduleNotify = \Yii::$app->getModule('notify');

$moduleSeo = \Yii::$app->getModule('seo');
$hideSeoModuleClass = $newsModule->hideSeoModule ? ' hidden' : '';

$reportModule = \Yii::$app->getModule('report');

$moduleTag = \Yii::$app->getModule('tag');
$activateTagFree = (isset($newsModule->enableFreeTags)) ? $newsModule->enableFreeTags : false;
$tagTitle = ($enableAgid ? AmosNews::t('amosnews', '#tag') : AmosNews::t('amosnews', '#tags_title'));

// ENABLE AGID FIELDS
$enableAgid = $newsModule->enableAgid;
$rtePlugins = $newsModule->rtePlugins;
$rteToolbar = $newsModule->rteToolbar;
$eventsModule = Yii::$app->getModule('events');

$textAlertCategory = AmosNews::t('news', "Questa categoria è già stata scelta come categoria principale.");

/** @var AmosComments $moduleComments */
$moduleComments = \Yii::$app->getModule('comments');
$renderFormSectionComments = ($moduleComments && in_array(get_class($model), $moduleComments->modelsEnabled));

// Comments moderation
if (!$model->isNewRecord && $moduleComments && $model->comments_enabled && $model->checkCommentsModeration()) {
    $moderationValue = $moduleComments->getContextModerationValue($model->modelClassName, $model->id);
    // Modal confirm moderation
    echo $this->render('@vendor/open20/amos-comments/src/views/comment-dashboard/parts/_modal');

    $jsSwitchInputChange = <<<JS
    function switchInputChange(e, state) {
        var value = e.target.checked;
        var modal = $('.modal-comments-moderation');
        var confirmBtn = $('.modal-comments-moderation #modalConfirmBtn');
        var switchInput = $(this);
        var moderationValue = '{$moderationValue}';
        
        if (state === false && moderationValue == true) {
            switchInput.bootstrapSwitch('state', !state, true);
            modal.modal('show');
            confirmBtn.on('click', function (e) {
                switchInput.bootstrapSwitch('state', state, true);
                modal.modal('hide');
            });
        }
    }
JS;
}

$js2 = <<<JS
  $(document).ready(function () {
    if($("#news_categorie_id-id option").length == 2){
      $($("#news_categorie_id-id option").parent().parent().parent()).hide();
    }
  });

JS;

$jsOtherCategory = <<<JS

     $(document).on('select2:selecting', '#news_categorie_mm_id-id', function(e){
          var selected = e.params.args.data.id;
          var current =  $('#news_categorie_id-id').val();
          
         if(current === selected){
            alert("$textAlertCategory");
            return false;
         }
     });
     
     $('#news_categorie_id-id').change(function(){
        var current = $(this).val();
        var currentText = $(this).text();
        
         var a = $('#news_categorie_mm_id-id').val();
         var text = String(a);
         var otherCategories = text.split(',');
         if(otherCategories.includes(current)){
             otherCategories.forEach(function (item, index) {
                if(item == current){
                    otherCategories.splice(index, 1);
                    $('#news_categorie_mm_id-id').val(otherCategories.join(','));
                    $('#news_categorie_mm_id-id').trigger('change');
                }
            });
         }
    });

JS;

$this->registerJs($js2);
if ($enableOtherNewsCategories) {
    $this->registerJs($jsOtherCategory);
}

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
$enableGalleryAttachment = $newsModule->enableGalleryAttachment;
$enableRelateEvents = $newsModule->enableRelateEvents;

$hidePreviewDeleteButton = false;
if ($enableAgid) {
    $newsImageElementId = Html::getInputId($model, 'newsImage');
    $hidePreviewDeleteButton = true;
    $jsAgid = <<<JS
        function addRequiredAsterisk(fieldName) {
            $('.field-' + fieldName).addClass('required');
        }
        addRequiredAsterisk('$newsImageElementId');
JS;
    $this->registerJs($jsAgid);
    $newsImageLabel = AmosNews::t('amosnews', '#image_field_required');
} else {
    $newsImageLabel = $model->getAttributeLabel('newsImage');
}

$form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
        'class' => 'news-aform',
    ]

]);
?>
<?php
// id 20683: CHECK/VALIDATE FIELDS "DATE PUBBLICAZIONE" ON FOCUSOUT AND KEYUP
$this->registerJs(
    <<<JS

$("#news-data_pubblicazione-disp").on("keyup", function () {
  validateDatePubblicazioneFields();
});

$("#news-data_rimozione-disp").on("keyup", function () {
  validateDatePubblicazioneFields();
});

$("#news-data_pubblicazione-disp").on("focusout", function () {
  validateDatePubblicazioneFields();
});

$("#news-data_rimozione-disp").on("focusout", function () {
  validateDatePubblicazioneFields();
});

window.validateDatePubblicazioneFields = function () {
  setTimeout(function () {
    $('.news-aform').yiiActiveForm('validateAttribute', 'news-data_pubblicazione');
    $('.news-aform').yiiActiveForm('validateAttribute', 'news-data_rimozione');
  }, 1000);
}
JS
);

?>
<div class="news-form">
    <div class="row">
        <div class="col-xs-12">
            <?php
            $reportFlagWidget = '';
            if (isset($reportModule) && in_array($model->className(), $reportModule->modelsEnabled)) {
                $reportFlagWidget = \open20\amos\report\widgets\ReportFlagWidget::widget([
                    'model' => $model
                ]);
            }
            ?>
        </div>

        <!--contenuti multimediali-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Immagine principale') ?></h2>
            <div class="row">
                <div class="col-xs-12">
                    <?=
                    $form->field($model, 'newsImage')->widget(CropInput::class, [
                        'hidePreviewDeleteButton' => $hidePreviewDeleteButton,
                        'jcropOptions' => ['aspectRatio' => '1.7']
                    ])->label($newsImageLabel)->hint(AmosNews::t('amosnews', '#image_field_hint'))
                    ?>
                </div>
            </div>
            <div>
                <?= $form->field($model, 'alt')->textInput(['maxlength' => true, 'placeholder' => AmosNews::t('amosnews', "Inserisci l'alt")])->label(AmosNews::t('amosnews', 'Alt'))->hint(AmosNews::t('amosnews', '#title_field_hint')) ?>
            </div>
        </div>

        <!--nome e categoria-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Informazioni di base') ?></h2>
            <div class="row">
                <div class="col-xs-12">
                    <?= $form->field($model, 'titolo')->textInput(['maxlength' => true, 'placeholder' => AmosNews::t('amosnews', '#title_field_plceholder')])->hint(AmosNews::t('amosnews', '#title_field_hint')) ?>
                </div>

                <?php if (!$enableAgid) : ?>
                    <div class="col-xs-12">
                        <?= $form->field($model, 'sottotitolo')->textInput([
                            'maxlength' => true,
                            'placeholder' => AmosNews::t('amosnews', '#subtitle_field_plceholder')
                        ])->hint($model->attributeHints()['sottotitolo']) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($moduleTag) && $activateTagFree) : ?>
                    <div class="col-xs-12">
                        <?=
                        $form->field($model, 'tag_free')->widget(\xj\tagit\Tagit::className(), [
                            'clientOptions' => [
                                'tagSource' => \yii\helpers\Url::to(['/tag/manager/autocomplete-free-tag', 'id' => ($model->isNewRecord ? null : $model->id)]),
                                'autocomplete' => [
                                    'delay' => 200,
                                    'minLength' => 3,
                                ],
                                'singleField' => true,
                                'beforeTagAdded' => new \yii\web\JsExpression(
                                    <<<EOF
                            function(event, ui){
                                if (!ui.duringInitialization) {
                                    /*console.log(event);
                                    console.log(ui);*/
                                }
                            }
EOF
                                ),
                            ],
                        ]);
                        ?>
                    </div>
                <?php endif; ?>

            </div>

            <!-- AGID FIELD -->
            <div class="row">
                <?php if ($enableAgid && $newsModule->enableAgidNewsContentType) : ?>
                    <div class="col-xs-12">
                        <?= $form->field($model, 'news_content_type_id')->widget(Select2::class, ['data' => ArrayHelper::map(NewsContentType::find()->asArray()->all(), 'id', 'name'), 'language' => substr(Yii::$app->language, 0, 2), 'options' => ['id' => 'news_content_type_id', 'multiple' => false, 'placeholder' => AmosNews::t('amosnews', 'Seleziona') . ' ...'], 'pluginOptions' => ['allowClear' => true]])->hint(AmosNews::t('amosnews', '#news_content_type_id'))->label(AmosNews::t('amosnews', '#news_content_type_id')); ?>
                    </div>
                <?php endif; ?>

                <div class="col-xs-12">
                    <?php
                    $labelCategory = $enableOtherNewsCategories ? AmosNews::t('amosnews', "Main category") : $model->attributeLabels()['news_categorie_id'];
                    if ($model->isNewRecord && empty($model->news_categorie_id) && !empty(AmosNews::instance()->defaultCategory)) {
                        $model->news_categorie_id = AmosNews::instance()->defaultCategory;
                    }

                    $newsCategoriesQuery = NewsUtility::getNewsCategories()->orderBy('titolo');
                    $otherNewsCategoriesQuery = clone $newsCategoriesQuery;
                    if ($enableOtherNewsCategories) {
                        $otherNewsCategoriesQuery->andWhere([
                            'not in',
                            'news_categorie.id',
                            $model->news_categorie_id
                        ]);
                    }
                    $newsCategories = $newsCategoriesQuery->all();
                    $newsCategoryId = $model->news_categorie_id;

                    if (!$model->news_categorie_id && (count($newsCategories) == 1)) {
                        $newsCategoryId = $newsCategories[0]->id;
                    } else {
                        $newsCategoryId = 1;
                    }
                    ?>
                    <?= $form->field($model, 'news_categorie_id')->widget(Select::class, [
                        'auto_fill' => true,
                        'options' => [
                            'placeholder' => AmosNews::t('amosnews', '#category_field_placeholder'),
                            'id' => 'news_categorie_id-id',
                            'disabled' => false,
                            'value' => $model->isNewRecord ? $newsCategoryId : $model->news_categorie_id
                        ],
                        'data' => ArrayHelper::map($newsCategories, 'id', 'titolo')
                    ])->label($labelCategory);
                    ?>
                </div>

                <?php if ($enableOtherNewsCategories) { ?>
                    <div class="col-md-12">
                        <?= $form->field($model, 'otherCategories')->widget(Select2::className(), ['options' => ['placeholder' => AmosNews::t('amosnews', '#category_field_placeholder'), 'id' => 'news_categorie_mm_id-id', 'multiple' => true], 'pluginOptions' => ['allowClear' => true], 'data' => ArrayHelper::map($otherNewsCategoriesQuery->all(), 'id', 'titolo')])->label(AmosNews::t('amosnews', 'Other categories')) ?>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!--testo della notizia-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Testi') ?></h2>
            <div class="row">
                <div class="col-xs-12">
                    <?= $form->field($model, 'descrizione_breve')->textarea([
                        'maxlength' => true,
                        'placeholder' => AmosNews::t('amosnews', '#abstract_field_placeholder')
                    ])->hint($model->attributeHints()['descrizione_breve']) ?>
                </div>

                <div class="col-xs-12">
                    <?php
                    $clientOption = [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        'plugins' => $rtePlugins,
                        'toolbar' => $rteToolbar
                    ];
                    $hint = '';
                    if ($newsModule->textEditorClientOptions) {
                        $hint = AmosIcons::show('info-outline') . ' ' . AmosNews::t('amosnews', '#formatted_text');
                        if (empty($newsModule->arrayTextEditorClients) || in_array(\Yii::$app->user->id, $newsModule->arrayTextEditorClients)) {
                            $clientOption['paste_as_text'] = false;
                        }
                        if (!empty($newsModule->arrayTextEditorClients) && in_array(\Yii::$app->user->id, $newsModule->arrayTextEditorClients)) {
                            $hint = AmosIcons::show('info-outline') . ' ' . AmosNews::t('amosnews', '#formatted_text_for_you');
                        } else {
                            $hint = '';
                        }
                    }
                    echo $form->field($model, 'descrizione')
                        ->widget(TextEditorWidget::class, [
                            'options' => [
                                'placeholder' => AmosNews::t('amosnews', 'Inserisci...'),
                                'triggerTextareaInput' => true
                            ],
                            'clientOptions' => $clientOption
                        ])
                        ->hint($hint);
                    ?>
                </div>
            </div>
        </div>

        <!--documenti e allegati-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Allegati') ?></h2>
            <div class="row">
                <!-- AGID FIELD -->
                <?php if (($enableAgid) && ($enableAgidAllegati)) : ?>
                    <div class="col-xs-12">
                        <?= $form->field($model, 'news_documento_id')->widget(Select2::class, ['data' => ArrayHelper::map(\open20\amos\documenti\models\Documenti::find()->andwhere(['status' => 'DocumentiWorkflow/VALIDATO'])->andWhere(['deleted_at' => null])->all(), 'id', 'titolo'), 'language' => substr(Yii::$app->language, 0, 2), 'options' => ['id' => 'news_documento_id', 'multiple' => false, 'placeholder' => AmosNews::t('amosnews', 'Seleziona') . ' ...'], 'pluginOptions' => ['allowClear' => true]])->hint(AmosNews::t('amosnews', '#news_documento_id')); ?>
                    </div>
                <?php endif; ?>


                <div class="col-xs-12">
                    <?php
                    $moduleAttachment = \Yii::$app->getModule('attachments');
                    $extensions = $moduleAttachment->whiteListExtensions;
                    $hint = '';
                    if ($extensions) {
                        $hintExt = FileModule::t('amosattachments', "Estensioni consentite") . ': ' . implode(', ', $extensions);
                        $hint = Html::tag('span', '', ['class' => 'am am-info-outline m-l-5', 'data-toggle' => 'tooltip', 'title' => $hintExt]);
                    }
                    ?>
                    <?=
                    $form->field($model, 'attachments')->widget(AttachmentsInput::class, [
                        'options' => [
                            'multiple' => true,
                            'placeholder' => ''
                        ],
                        'pluginOptions' => [
                            'maxFileCount' => 100,
                            'showPreview' => false,
                            'msgPlaceholder' => FileModule::t('amosattachments', "Nessun allegato inserito")
                        ]
                    ])->label(FileModule::t('amosattachments', 'Allegato') . $hint);
                    ?>
                    <?= AttachmentsList::widget(['model' => $model, 'attribute' => 'attachments', 'label' => '']) ?>
                </div>
            </div>
        </div>

        <!-- Point of interest -->
        <?php if ($newsModule->enableMap && !empty(Yii::$app->params['google_places_api_key'])) { ?>
            <div class="col-xs-12 section-form m-t-30">
                <h2 class="subtitle-form"><?= BaseAmosModule::t('amoscore', 'Punto di interesse') ?></h2>

                <?= $form->field($model, 'place_id')->widget(PlaceWidget::className(), [])->label(false); ?>

            </div>
        <?php } ?>

        <?php if ($enableGalleryAttachment) : ?>
            <!--Gallery, inserimento di più immagini-->
            <div class="col-xs-12 section-form">
                <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Gallery') ?></h2>
                <div class="row">
                    <div class="col-xs-12">
                        <?=
                        $form->field($model, 'news_gallery_attachment')->widget(AttachmentsInput::className(), [
                            'options' => [ // Options of the Kartik's FileInput widget
                                'multiple' => true,
                                'accept' => ".jpg, .jpeg, .png",
                            ],
                            'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                                'maxFileCount' => 100, // Client max files
                                'showPreview' => true,
                                'allowedPreviewTypes' => ['image'],
                                'maxFileSize' => 5 * 1024 * 1024,
                            ]
                        ])->hint(AmosNews::t('amosnews', '#gallery_field_hint'));
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>


        <?php if ($eventsModule && $enableRelateEvents) { ?>
            <!--Eventi, inserimento di più eventi da mettere in relazione con seguente news-->
            <div class="col-xs-12 section-form">
                <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Eventi Correlati') ?></h2>
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <?= $form->field($model, 'newsRelatedEventMmAttribute')->widget(Select2::class, [
                            'data' => ArrayHelper::map(
                                \open20\amos\events\models\Event::find()
                                    ->andwhere(['status' => 'EventWorkflow/PUBLISHED'])
                                    ->andWhere(['deleted_at' => null])
                                    //->andWhere(['end_date_hour' => date("y:m:d h:i:sa")])
                                    ->all(),
                                'id',
                                'title'
                            ),
                            'options' => [
                                'multiple' => true,
                                'placeholder' => AmosNews::t('amosnews', 'Select...')
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ]
                        ])->hint(AmosNews::t('amosnews', '#event_field_hint'));
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!--referenti-->
        <!-- AGID FIELD -->
        <?php if (($enableAgid) && ($enableAgidReferenti)) : ?>
            <div class="col-xs-12 section-form">
                <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Referenti') ?></h2>
                <div class="row">

                    <div class="col-xs-12">
                        <!-- AGID FIELD -->
                        <?= $form->field($model, 'edited_by_agid_organizational_unit_id')->widget(Select2::class, ['data' => ArrayHelper::map(AgidOrganizationalUnit::find()->asArray()->all(), 'id', 'name'), 'language' => substr(Yii::$app->language, 0, 2), 'options' => ['id' => 'edited_by_agid_organizational_unit_id', 'multiple' => false, 'placeholder' => AmosNews::t('amosnews', 'Seleziona') . ' ...'], 'pluginOptions' => ['allowClear' => true]])->hint(AmosNews::t('amosnews', '#edited_by_agid_organizational_unit_id')); ?>
                    </div>
                    <div class="col-xs-12">
                        <!-- AGID FIELD -->
                        <?php
                        foreach ($model->newsAgidPersonMm as $key => $value) {
                            $news_agid_person_mm[] = $value->agid_person_id;
                        }

                        echo $form->field($model, 'news_agid_person_mm[]')
                            ->widget(\kartik\select2\Select2::class, [
                                'data' => ArrayHelper::map(\open20\agid\person\models\AgidPerson::find()->select([
                                    "id, name, surname, CONCAT(surname, ' ', name) AS surnameName"
                                ])
                                    ->andWhere([
                                        'agid_person_type_id' => AgidPersonType::find()->andWhere([
                                            'name' => "Politica"
                                        ])
                                            ->one()->id
                                    ])
                                    ->andWhere([
                                        'deleted_at' => null
                                    ])
                                    ->all(), 'id', 'surnameName'),
                                'options' => [
                                    'placeholder' => Yii::t('amosnews', 'Seleziona...'),
                                    'multiple' => true,
                                    'value' => isset($news_agid_person_mm) ? $news_agid_person_mm : null
                                ]
                            ])
                            ->label(Yii::t('amosnews', 'Persone'))
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- AGID FIELD -->
        <?php if ($enableAgid) : ?>
            <?php
            if (!is_null($siteManagementModule)) {
                $siteManagementModule::setExternalPreviousSessionKeys(Url::current(), $model->getGrammar()->getModelSingularLabel() . ' ' . $model->getTitle());
            }
            ?>
            <div class="row">
                <div class="col-xs-12">
                    <?php
                    if (!$model->isNewRecord) {
                        $max = SiteManagementSliderElem::find()->andWhere([
                            'slider_id' => $model->image_site_management_slider_id
                        ])->max("site_management_slider_elem.order");
                        $min = SiteManagementSliderElem::find()->andWhere([
                            'slider_id' => $model->image_site_management_slider_id
                        ])->min("site_management_slider_elem.order");
                        $newsModel = $model;
                    ?>
                        <h3><?= AmosNews::t('amosnews', 'Galleria immagini') ?></h3>
                        <?= Html::a(AmosNews::t('amosnews', 'Aggiungi immagine'), ['/sitemanagement/site-management-slider-elem/create', 'id' => $slider_image->id, 'slider_type' => SiteManagementSliderElem::TYPE_IMG, "image", 'urlRedirect' => urlencode('/news/news/update?id=' . $newsModel->id . '#tab-immagine'), 'useCrop' => true, 'cropRatio' => 1.7], ['class' => 'btn btn-navigation-primary', 'data-confirm' => AmosNews::t('amosnews', 'Stai per lasciare la pagina, assicurarsi di aver salvato dati. Proseguire?')]); ?>
                        <?php
                        $gridColumns = [
                            'order',
                            'type' => [
                                'attribute' => 'type',
                                'value' => function ($model) {
                                    return $model->getLabelType();
                                },
                                'enableSorting' => false
                            ],
                            'title' => [
                                'attribute' => 'title',
                                'enableSorting' => false
                            ],
                            [
                                'class' => \open20\amos\core\views\grid\ActionColumn::class,
                                'controller' => 'site-management-slider-elem',
                                'template' => '{update}{delete}',
                                'buttons' => [
                                    'update' => function ($url, $model) use ($newsModel) {
                                        return Html::a(\open20\amos\core\icons\AmosIcons::show('edit'), [
                                            '/sitemanagement/site-management-slider-elem/update',
                                            'id' => $model->id,
                                            'urlRedirect' => urlencode('/news/news/update?id=' . $newsModel->id . '#tab-immagine')
                                        ], [
                                            'class' => 'btn btn-navigation-primary'
                                        ]);
                                    },
                                    'view' => function ($url, $model) use ($newsModel) {
                                        return Html::a(\open20\amos\core\icons\AmosIcons::show('file'), [
                                            '/sitemanagement/site-management-slider-elem/view',
                                            'id' => $model->id,
                                            'urlRedirect' => urlencode('/news/news/update?id=' . $newsModel->id . '#tab-immagine')
                                        ], [
                                            'class' => 'btn btn-navigation-primary'
                                        ]);
                                    },
                                    'delete' => function ($url, $model) use ($newsModel) {
                                        return Html::a(\open20\amos\core\icons\AmosIcons::show('delete'), [
                                            '/sitemanagement/site-management-slider-elem/delete',
                                            'id' => $model->id,
                                            'urlRedirect' => urlencode('/news/news/update?id=' . $newsModel->id . '#tab-immagine')
                                        ], [
                                            'class' => 'btn btn-danger-inverse'
                                        ]);
                                    }
                                ]
                            ]
                        ];

                        if ($readOnly) {
                            array_pop($gridColumns);
                        }
                        echo \open20\amos\core\views\AmosGridView::widget([
                            'dataProvider' => $dataProviderSliderElemImage,
                            'columns' => $gridColumns
                        ]);
                        ?>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-xs-12">
                    <?php if (!$model->isNewRecord) : ?>
                        <h3><?= AmosNews::t('amosnews', 'Video notizia') ?></h3>
                        <?= Html::a(\Yii::t('amosnews', 'Aggiungi video'), ['/sitemanagement/site-management-slider-elem/create', 'id' => $slider_video->id, 'slider_type' => SiteManagementSliderElem::TYPE_VIDEO, 'urlRedirect' => urlencode('/news/news/update?id=' . $newsModel->id . '#tab-video'), 'useCrop' => true, 'cropRatio' => 1.7], ['class' => 'btn btn-navigation-primary', 'data-confirm' => AmosNews::t('amosnews', 'Stai per lasciare la pagina, assicurarsi di aver salvato dati. Proseguire?')]); ?>
                        <?php
                        $gridColumns1 = [
                            'order',
                            'type' => [
                                'attribute' => 'type',
                                'value' => function ($model) {
                                    return $model->getLabelType();
                                },
                                'enableSorting' => false
                            ],
                            'title' => [
                                'attribute' => 'title',
                                'enableSorting' => false
                            ],
                            [
                                'class' => \open20\amos\core\views\grid\ActionColumn::class,
                                'controller' => 'site-management-slider-elem',
                                'template' => '{update}{delete}',
                                'buttons' => [
                                    'update' => function ($url, $model) use ($newsModel) {
                                        return Html::a(\open20\amos\core\icons\AmosIcons::show('edit'), [
                                            '/sitemanagement/site-management-slider-elem/update',
                                            'id' => $model->id,
                                            'urlRedirect' => urlencode('/news/news/update?id=' . $newsModel->id . '#tab-video')
                                        ], [
                                            'class' => 'btn btn-navigation-primary'
                                        ]);
                                    },
                                    'view' => function ($url, $model) use ($newsModel) {
                                        return Html::a(\open20\amos\core\icons\AmosIcons::show('file'), [
                                            '/sitemanagement/site-management-slider-elem/view',
                                            'id' => $model->id,
                                            'urlRedirect' => urlencode('/news/news/update?id=' . $newsModel->id . '#tab-video')
                                        ], [
                                            'class' => 'btn btn-navigation-primary'
                                        ]);
                                    },
                                    'delete' => function ($url, $model) use ($newsModel) {
                                        return Html::a(\open20\amos\core\icons\AmosIcons::show('delete'), [
                                            '/sitemanagement/site-management-slider-elem/delete',
                                            'id' => $model->id,
                                            'urlRedirect' => urlencode('/news/news/update?id=' . $newsModel->id . '#tab-video')
                                        ], [
                                            'class' => 'btn btn-danger-inverse'
                                        ]);
                                    }
                                ]
                            ]
                        ];

                        if ($readOnly) {
                            array_pop($gridColumns);
                        }
                        echo \open20\amos\core\views\AmosGridView::widget([
                            'dataProvider' => $dataProviderSliderElemVideo,
                            'columns' => $gridColumns1
                        ]);
                        ?>

                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!--altre informazioni-->
        <!-- AGID FIELD -->
        <?php if ($enableAgid) : ?>
            <div class="col-xs-12">
                <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Altre informazioni') ?></h2>
                <div class="row">

                    <div class="col-md-6 col-xs-12">
                        <!-- AGID FIELD -->
                        <?php
                        if ($model->isNewRecord) {
                            $model->date_news = date("Y-m-d");
                        }
                        ?>
                        <?= $form->field($model, 'date_news')->widget(DateControl::class, ['value' => $model->isNewRecord ? date("Y-m-d") : ''])->hint(AmosNews::t('amosnews', 'date_news'))->label(AmosNews::t('amosnews', 'date_news')); ?>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <!-- AGID FIELD -->
                        <?= $form->field($model, 'news_expiration_date')->widget(DateControl::className(), [])->hint(AmosNews::t('amosnews', 'news_expiration_date'))->label(AmosNews::t('amosnews', 'news_expiration_date')); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-xs-12">

                        <!-- NEWS AGID FIELD -->
                        <?php if ($newsModule->enableAgidNewsRelatedNews == true) : ?>
                            <?php
                            foreach ($model->newsRelatedNewsMm as $key => $value) {
                                $news_related_news_mm[] = $value->related_news_id;
                            }

                            $query = \open20\amos\news\models\News::find()->andwhere([
                                'status' => News::NEWS_WORKFLOW_STATUS_VALIDATO
                            ])->andWhere([
                                'deleted_at' => null
                            ]);
                            if (!empty($model->id)) {
                                $query->andWhere([
                                    '!=',
                                    'id',
                                    $model->id
                                ]);
                            }

                            echo $form->field($model, 'news_related_news_mm[]')
                                ->widget(Select2::class, [
                                    'data' => ArrayHelper::map($query->all(), 'id', 'titolo'),
                                    'language' => substr(Yii::$app->language, 0, 2),
                                    'options' => [
                                        'id' => 'news_related_news_mm',
                                        'multiple' => true,
                                        'placeholder' => AmosNews::t('amosnews', 'Seleziona') . ' ...',
                                        'value' => isset($news_related_news_mm) ? $news_related_news_mm : null
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ]
                                ])
                                ->hint(AmosNews::t('amosnews', 'news_related_news_mm'));
                            ?>
                    </div>
                <?php endif; ?>

                <div class="col-md-6 col-xs-12">
                    <?php if ($newsModule->enableAgidNewsRelatedDocumenti == true) : ?>
                        <?php
                        foreach ($model->newsRelatedDocumentiMm as $key => $value) {
                            $news_related_documenti_mm[] = $value->related_documenti_id;
                        }
                        ?>
                        <?= $form->field($model, 'news_related_documenti_mm[]')->widget(Select2::class, ['data' => ArrayHelper::map(\open20\amos\documenti\models\Documenti::find()->andwhere(['status' => 'DocumentiWorkflow/VALIDATO'])->andWhere(['deleted_at' => null])->all(), 'id', 'titolo'), 'language' => substr(Yii::$app->language, 0, 2), 'options' => ['id' => 'news_related_documenti_mm', 'multiple' => true, 'placeholder' => 'Seleziona ...', 'value' => isset($news_related_documenti_mm) ? $news_related_documenti_mm : null], 'pluginOptions' => ['allowClear' => true]])->hint(AmosNews::t('amosnews', 'news_related_documenti_mm')); ?>
                    <?php endif; ?>
                </div>
                </div>
                <div class="row">

                    <div class="col-md-6 col-xs-12">
                        <?php if ($newsModule->enableAgidNewsRelatedAgidService == true) : ?>
                            <?php
                            foreach ($model->newsRelatedAgidServiceMm as $key => $value) {
                                $news_related_agid_service_mm[] = $value->related_agid_service_id;
                            }
                            ?>
                            <?= $form->field($model, 'news_related_agid_service_mm[]')->widget(Select2::class, ['data' => ArrayHelper::map(\open20\agid\service\models\AgidService::find()->andWhere(['status' => 'AgidServiceWorkflow/VALIDATED'])->andWhere(['deleted_at' => null])->all(), 'id', 'name'), 'language' => substr(Yii::$app->language, 0, 2), 'options' => ['id' => 'news_related_agid_service_mm', 'multiple' => true, 'placeholder' => AmosNews::t('amosnews', 'Seleziona') . ' ...', 'value' => isset($news_related_agid_service_mm) ? $news_related_agid_service_mm : null], 'pluginOptions' => ['allowClear' => true]])->hint(AmosNews::t('amosnews', 'news_related_agid_service_mm')); ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 col-xs-12"></div>
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
        if ($model->isNewRecord) {
            if (!empty($scope)) {
                $pubblicatedForCommunity = true;
            }
        }
        ?>
        <?php
        if (isset($moduleTag)) :
        ?>
            <div class="col-xs-12 section-form">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="section-modalita-pubblicazione">
                            <?php if (\Yii::$app->user->can('ADMIN') || \Yii::$app->user->can('ADMIN_ARIA')) { ?>
                                <?= Html::tag('h2', $tagTitle, ['class' => 'subtitle-form']) ?>
                            <?php } ?>
                            <?php
                            echo \open20\amos\cwh\widgets\DestinatariPlusTagWidget::widget([
                                'model' => $model,
                                'moduleCwh' => $moduleCwh,
                                'scope' => $scope
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <div class="col-xs-12 section-form">
            <!-- < ?php echo Html::tag('h2', '', ['class' => 'subtitle-form']) ?> -->
            <div class="row">

                <?php
                $isCommunityManager = \open20\amos\community\utilities\CommunityUtil::isLoggedCommunityManager();
                $publish_enabled = !empty($newsModule->params['publication_always_enabled']) || (\Yii::$app->user->can('NEWS_PUBLISHER_FRONTEND') && empty($scope) && !$pubblicatedForCommunity);

                $adminOrSuperUser = false;
                if (\Yii::$app->user->can('ADMIN') || \Yii::$app->user->can('ADMIN_ARIA')) {
                    $adminOrSuperUser = true;
                }

                if ($newsModule->request_publish_on_hp == true) {
                    $isCommunityManager = $model->isCommunityManagerLoggedUserInThisNews();

                    if (!$adminOrSuperUser) {
                        $publish_enabled = true;
                        $model->primo_piano = 0;
                        $model->in_evidenza = 0;
                    }
                }

                if ($enablePrimoPianoOnCommunity && $isCommunityManager) {
                    $publish_enabled = true;
                }

                if ($publish_enabled) {
                    if ($newsModule->params['site_publish_enabled'] || $newsModule->params['site_featured_enabled']) {
                ?>
                        <div class="col-xs-12">
                            <div class="row">
                                <?php if ($newsModule->params['site_publish_enabled']) { ?>
                                    <!-- <h3 class="subtitle-section-form">< ?= AmosNews::t('amosnews', "Pubblication on the portal mode") ?>
                                                <em>(< ?= AmosNews::t('amosnews', "Choose if you want to publish the news also on the portal") ?>
                                                    )</em>
                                            </h3> -->
                                <?php
                                    $attrDisabledPrimoPiano = false;
                                    $hiddenPrimoPiano = '';

                                    if ($model->isNewRecord) {
                                        if (empty($scope)) {
                                            $model->primo_piano = 1;
                                            $model->in_evidenza = 0;
                                        } else {
                                            $model->primo_piano = 0;
                                            $model->in_evidenza = 0;
                                        }
                                    }
                                    if(isset($newsModule->disableFormParamPrimoPiano) && $newsModule->disableFormParamPrimoPiano) {
                                        $attrDisabledPrimoPiano = true;
                                        $hiddenPrimoPiano = '<div class="hide">' . $form->field($model, 'primo_piano')->hiddenInput(['value' => $model->primo_piano])->label(false) . '</div>';
                                    }

                                    $primoPiano = '<div class="col-md-6 col-xs-12">' . $form->field($model, 'primo_piano')->widget(Select::class, [
                                        'auto_fill' => true,
                                        'disabled' => $attrDisabledPrimoPiano,
                                        'data' => [
                                            '0' => AmosNews::t('amosnews', 'solo a utenti registrati'),
                                            '1' => AmosNews::t('amosnews', 'a utenti registrati e non registrati')
                                        ],
                                        'options' => [
                                            'prompt' => AmosNews::t('amosnews', 'Seleziona'),
                                            'onchange' => "
                                                    if($(this).val() == 1) $('#news-in_evidenza').prop('disabled', false);
                                                    if($(this).val() == 0) {
                                                        $('#news-in_evidenza').prop('disabled', true);
                                                        $('#news-in_evidenza').val(0).change();
                                                    }
                                                "
                                        ]
                                    ])->label('Mostra notizia')->hint('Scegli se rendere la notizia fruibile anche da un\'utenza non registrata') . '</div>';
                                    if ($publish_enabled) {
                                        echo $primoPiano;
                                        echo $hiddenPrimoPiano;
                                    }
                                }

                                if ($newsModule->params['site_featured_enabled']) {
                                    $inEvidenza = '<div class="col-md-6 col-xs-12">' . $form->field($model, 'in_evidenza')->widget(Select::class, [
                                        'auto_fill' => true,
                                        'data' => [
                                            '0' => AmosNews::t('amosnews', 'No'),
                                            '1' => AmosNews::t('amosnews', 'Si')
                                        ],
                                        'options' => [
                                            'prompt' => AmosNews::t('amosnews', 'Seleziona'),
                                            'disabled' => (isset($enableAgid) && true == $enableAgid) ? false : ($model->primo_piano == 1 ? false : true)
                                        ]
                                    ])->label('notizia in evidenza')->hint('La notizia sarà visibile in apposite sezioni “vetrina” all\'interno delle pagine pubbliche') . '</div>';
                                    if ($publish_enabled) {
                                        if ($newsModule->request_publish_on_hp && (!$adminOrSuperUser)) {
                                            // se request_publish_on_hp ma non sono un uttente abilitato a mettere in evidenza... tolgo il campo!
                                            echo '';
                                        } else {
                                            echo $inEvidenza;
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>

                <?php
                    }
                }
                if ($newsModule->request_publish_on_hp) {
                    $request_hp = '<div class="col-md-6 col-xs-12">' . $form->field($model, 'request_publish_on_hp')
                        ->widget(Select::class, [
                            'auto_fill' => true,
                            'data' => [
                                '0' => AmosNews::t('amosnews', 'No'),
                                '1' => AmosNews::t('amosnews', 'Si')
                            ],
                            'options' => [
                                'prompt' => AmosNews::t('amosnews', 'Seleziona'),
                                'disabled' => $adminOrSuperUser ? true : false
                            ]
                        ])
                        ->label(AmosNews::t('amosnews', '#placeholder_for_choose_to_publish_on_hp')) . '</div>';

                    // solo se sono community manager posso richiedere ad un admin oppure ad un super user la pubbicazione in evideza!
                    if ($isCommunityManager) {
                        echo $request_hp;
                    }
                }
                ?>
            </div>
            <!-- </div> -->
        </div>

        <?php
        if (\Yii::$app->getModule('correlations')) {
            echo open2\amos\correlations\widget\ManageCorrelationsButtonWidget::widget([
                'model' => $model
            ]);
        }
        ?>

    </div>

    <div class="row">
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Impostazioni per la pubblicazione programmata') ?></h2>
            <div class="row">
                <?php
                if ($model->data_rimozione == '9999-12-31 00:00:00') {
                    $model->data_rimozione = null;
                }
                if ($newsModule->hidePubblicationDate == false) {
                    $tooltipDataPubblicazione = '<span class="mdi mdi-information-outline" title="' . AmosNews::t('amosnews', 'Se non impostata la data di pubblicazione, la notizia sarà pubblicata immediatamente al passaggio in stato Pubblicata') . '" data-toggle="tooltip"></span>';
                    $publicationDate = Html::tag(
                        'div',
                        $form->field($model, 'data_pubblicazione')
                            ->widget(DateControl::class, [
                                'type' => $newsModule->dateFormatNews,
                                'widgetOptions' => [
                                    'options' => [
                                        'placeholder' => \Yii::t('amosnews', 'Pubblicazione immediata'),
                                    ],
                                    'pluginEvents' => [
                                        "changeDate" => "function () { validateDatePubblicazioneFields(); }",
                                    ],
                                ],
                            ])
                            ->label(AmosNews::t('amosnews', 'data inizio pubblicazione'))
                            ->hint(AmosNews::t('amosnews', 'La notizia sarà raggiungibile tramite url a partire da questa data {tooltipDataPubblicazione}', ['tooltipDataPubblicazione' => $tooltipDataPubblicazione])),
                        [
                            'class' => 'col-md-6 col-xs-12'
                        ]
                    ) . Html::tag(
                        'div',
                        $form->field($model, 'data_rimozione')
                            ->widget(DateControl::className(), [
                                'type' => $newsModule->dateFormatNews,
                                'widgetOptions' => [
                                    'options' => [
                                        'placeholder' => \Yii::t('amosnews', 'Sempre pubblica'),
                                    ],
                                    'pluginEvents' => [
                                        "changeDate" => "function () { validateDatePubblicazioneFields(); }",
                                    ],
                                ]
                            ])
                            ->label(AmosNews::t('amosnews', 'data fine pubblicazione'))
                            ->hint(AmosNews::t('amosnews', 'La pagina non sarà più raggiungibile tramite url a partire da questa data')),
                        [
                            'class' => 'col-md-6 col-xs-12'
                        ]
                    );
                } else {
                    $publicationDate = '';
                }
                ?>
                <?= $publicationDate ?>
            </div>

        </div>
    </div>
    <?php
    if ($moduleComments && $renderFormSectionComments) {
        if ($model->isNewRecord) { // default enable comment
            $model->comments_enabled = $newsModule->defaultEnableComments; ?>
            <div class="row">
                <div class="col-xs-12 section-form">

                    <h2 class="subtitle-form"><?= AmosNews::t('amosnews', 'Commenti') ?></h2>

                    <!-- Comments enabled -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="subcommunity-section" style="padding:24px 24px 0 24px">
                                <div class="row">
                                    <div class="col-xs-10 form-group m-b-0">
                                        <?= Html::label(
                                            $model->attributeLabels()['comments_enabled'],
                                            'comments_enabled',
                                            ['class' => 'control-label m-t-15']
                                        ); ?>
                                    </div>
                                    <div class="col-xs-2">
                                        <?= $form->field($model, 'comments_enabled')->widget(SwitchInput::classname(), [
                                            'pluginOptions' => [
                                                'onColor' => 'success',
                                                'onText' => AmosNews::t('amosnews', '#comments_ok'),
                                                'offText' => AmosNews::t('amosnews', '#comments_no'),
                                                'options' => [
                                                    'id' => 'comments_enabled'
                                                ]
                                            ],
                                        ])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php } else {
            // Comments moderation
            if ($model->comments_enabled && $model->checkCommentsModeration()) { ?>
                <div class="row">
                    <div class="col-xs-12 section-form">

                        <h2 class="subtitle-form"><?= AmosComments::t('amoscomments', 'Commenti') ?></h2>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="subcommunity-section">
                                    <div class="row">
                                        <div class="col-xs-10 form-group m-b-0">
                                            <?= Html::label(
                                                AmosComments::t('amoscomments', 'Attiva la moderazione dei commenti'),
                                                'comments_moderation',
                                                ['class' => 'control-label m-t-15']
                                            ); ?>
                                        </div>
                                        <div class="col-xs-2">
                                            <?= SwitchInput::widget([
                                                'name' => 'comments_moderation',
                                                'pluginOptions' => [
                                                    'onColor' => 'success',
                                                    'onText' => BaseAmosModule::t('amoscore', 'Sì'),
                                                    'offText' => BaseAmosModule::t('amoscore', 'No'),
                                                ],
                                                'value' => $moderationValue,
                                                'id' => 'comments_moderation',
                                                'pluginEvents' => [
                                                    "switchChange.bootstrapSwitch" => new \yii\web\JsExpression($jsSwitchInputChange),
                                                ]
                                            ]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    <?php
    $contentLanguage = '';
    if ($moduleNotify && !empty($moduleNotify->enableNotificationContentLanguage) && $moduleNotify->enableNotificationContentLanguage) {
    ?>
        <?php
        $contentLanguage = "<div class=\"row\"><div class=\"col-xs-12\">" . \open20\amos\notificationmanager\widgets\NotifyContentLanguageWidget::widget([
            'model' => $model
        ]) . "</div></div>"
        ?>
    <?php } ?>

    <?php if ($moduleSeo) : ?>
        <div class="<?= $hideSeoModuleClass ?>">
            <?= AccordionWidget::widget(['items' => [['header' => AmosNews::t('amosnews', '#settings_seo_title'), 'content' => \open20\amos\seo\widgets\SeoWidget::widget(['contentModel' => $model])]], 'headerOptions' => ['tag' => 'h2'], 'options' => (Yii::$app->user->can('ADMIN') || Yii::$app->user->can('CAPOREDATTORECMS')) ? [] : ['style' => 'display:none;'], 'clientOptions' => ['collapsible' => true, 'active' => 'false', 'icons' => ['header' => 'ui-icon-amos am am-plus-square', 'activeHeader' => 'ui-icon-amos am am-minus-square']]]); ?>
        </div>
    <?php endif; ?>

    <div class="col-xs-12"><?= RequiredFieldsTipWidget::widget() ?></div>

</div>
<div class="row">
    <?php
    $config = [
        'model' => $model,
        'urlClose' => Yii::$app->session->get('previousUrl'),
        'buttonClassSave' => 'btn btn-workflow'
    ];

    $statusToRenderToHide = $model->getStatusToRenderToHide();

    $draftButtons = [];
    if ($disableStandardWorkflow == false) {
        $draftButtons = [
            News::NEWS_WORKFLOW_STATUS_DAVALIDARE => [
                'button' => Html::submitButton(AmosNews::t('amosnews', 'Salva'), [
                    'class' => 'btn btn-workflow'
                ]),
                'description' => AmosNews::t('amosnews', 'le modifiche e mantieni la notizia in "richiesta di pubblicazione"')
            ],
            News::NEWS_WORKFLOW_STATUS_VALIDATO => [
                'button' => \open20\amos\core\helpers\Html::submitButton(AmosNews::t('amosnews', 'Salva'), [
                    'class' => 'btn btn-workflow'
                ]),
                'description' => AmosNews::t('amosnews', 'le modifiche e mantieni la notizia "pubblicata"')
            ],
            'default' => [
                'button' => Html::submitButton(AmosNews::t('amosnews', 'Salva in bozza'), [
                    'class' => 'btn btn-workflow'
                ]),
                'description' => AmosNews::t('amosnews', 'potrai richiedere la pubblicazione in seguito')
            ]
        ];
    }

    $addButtons = [
        'default' => [
            'default' => [
                'button' => Html::a(
                    AmosNews::t('amosnews', 'Anteprima'),
                    $model->isNewRecord ? '/news/news/create?urlRedirect=' . urlencode("/news/news/view") :
                        '/news/news/update?id=' . $model->id . "&urlRedirect=" . urlencode("/news/news/view?id=" . $model->id),
                    ['class' => 'btn btn-workflow', 'data' => ['method' => 'post',], 'target' => '_blank']
                ),
                'description' => AmosNews::t('amosnews', 'visualizza l\'anteprima della notizia'),
            ]
        ]
    ];
    $closeButtonUrl = !empty(\Yii::$app->request->get('urlRedirect')) ? \Yii::$app->request->get('urlRedirect') : Yii::$app->session->get('previousUrl');
    // forzo la chiusura del form indirizzata all'elenco delle news
    $closeButtonUrl = isset($this->params['forceBreadcrumbs'][0]['url']) ? $this->params['forceBreadcrumbs'][0]['url'] : $closeButtonUrl;

    echo WorkflowTransitionButtonsWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => News::NEWS_WORKFLOW,
        'viewWidgetOnNewRecord' => true,
        // 'closeSaveButtonWidget' => CloseSaveButtonWidget::widget($config),
        'closeButton' => Html::a(AmosNews::t('amosnews', 'indietro'), $closeButtonUrl, [
            'class' => 'btn btn-secondary'
        ]),
        'initialStatusName' => "BOZZA",
        'initialStatus' => News::NEWS_WORKFLOW_STATUS_BOZZA,
        'statusToRender' => $statusToRenderToHide['statusToRender'],
        // POII-1147 gli utenti validatore/facilitatore o ADMIN possono sempre salvare la news => parametro a false
        // altrimenti se stato VALIDATO => pulsante salva nascosto
        'hideSaveDraftStatus' => [], //$statusToRenderToHide['hideDraftStatus'],
        'draftButtons' => $draftButtons,
        'additionalButtons' => $addButtons
    ]);
    ?>
</div>
<?php ActiveForm::end(); ?>