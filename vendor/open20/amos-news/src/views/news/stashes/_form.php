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
use open20\amos\attachments\components\CropInput;
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\news\AmosNews;
use open20\amos\news\models\News;
use open20\amos\news\utility\NewsUtility;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use open20\amos\core\forms\TextEditorWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\News $model
 * @var yii\widgets\ActiveForm $form
 */
$dateErrorMessage = AmosNews::t('error', "Controllare data");

$todayDate    = date('d-m-Y');
$tomorrowDate = (new DateTime('tomorrow'))->format('d-m-Y');

//\open20\amos\layout\assets\SpinnerWaitAsset::register($this);
$js2 = <<<JS
    $(document).ready(function () {

        if($("#news_categorie_id-id option").length == 2){
            $($("#news_categorie_id-id option").parent().parent().parent()).hide();
        }

    });

JS;

$this->registerJs($js2);
?>

<?php
$form             = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'] // important
]);
$customView       = Yii::$app->getViewPath().'/imageField.php';
?>

<?=
WorkflowTransitionStateDescriptorWidget::widget([
    'form' => $form,
    'model' => $model,
    'workflowId' => News::NEWS_WORKFLOW,
    'classDivIcon' => '',
    'classDivMessage' => 'message',
    'viewWidgetOnNewRecord' => false
]);
?>

<div class="news-form">
    <div class="row">
        <div class="col-xs-12">
            <?php
            $reportModule     = \Yii::$app->getModule('report');
            $reportFlagWidget = '';
            if (isset($reportModule) && in_array($model->className(), $reportModule->modelsEnabled)) {
                $reportFlagWidget = \open20\amos\report\widgets\ReportFlagWidget::widget([
                    'model' => $model,
                ]);
            }
            ?>
            <?=
            Html::tag('h2',
                AmosNews::t('amosnews', '#settings_general_title').
                CreatedUpdatedWidget::widget(['model' => $model, 'isTooltip' => true]).
                $reportFlagWidget, ['class' => 'subtitle-form'])
            ?>
        </div>
        <div class="col-md-8 col-xs-12">
            <?=
            $form->field($model, 'titolo')->textInput(['maxlength' => true, 'placeholder' => AmosNews::t('amosnews',
                '#title_field_plceholder')])->hint(AmosNews::t('amosnews', '#title_field_hint'))
            ?>
            <?=
            $form->field($model, 'sottotitolo')->textInput(['maxlength' => true, 'placeholder' => AmosNews::t('amosnews',
                '#subtitle_field_plceholder')])->hint(AmosNews::t('amosnews', '#subtitle_field_hint'))
            ?>
            <?=
            $form->field($model, 'descrizione_breve')->textarea(['maxlength' => true, 'placeholder' => AmosNews::t('amosnews',
                '#abstract_field_placeholder')])->hint(AmosNews::t('amosnews', '#abstract_field_hint'))
            ?>
            <?=
            $form->field($model, 'descrizione')->widget(TextEditorWidget::className(),
                [
                    'clientOptions' => [
                        'placeholder' => AmosNews::t('amosnews', '#description_field_placeholder'),
                        'lang' => substr(Yii::$app->language, 0, 2)
                    ]
                ])
            ?>
            <?php
            if ($model->isNewRecord && !empty(AmosNews::instance()->defaultCategory)) {
                $model->news_categorie_id = AmosNews::instance()->defaultCategory;
            }
            $newsCategories = NewsUtility::getNewsCategories()->orderBy('titolo')->all();
            $newsCategoryId = $model->news_categorie_id;
            if (!$model->news_categorie_id && (count($newsCategories) == 1)) {
                $newsCategoryId = $newsCategories[0]->id;
            }
            ?>
            <?=
            $form->field($model, 'news_categorie_id')->widget(Select::className(),
                [
                    'auto_fill' => true,
                    'options' => [
                        'placeholder' => AmosNews::t('amosnews', '#category_field_placeholder'),
                        'id' => 'news_categorie_id-id',
                        'disabled' => false,
                        'value' => $newsCategoryId
                    ],
                    'data' =>
                        ArrayHelper::map(NewsUtility::getNewsCategories()
                            ->orderBy('titolo')->all(), 'id', 'titolo'),
                ]);
            ?>
        </div>
        <div class="col-md-4 col-xs-12">

            <div class="col-xs-12 nop">
                <?=
                $form->field($model, 'newsImage')->widget(CropInput::classname(),
                    [
                        'jcropOptions' => ['aspectRatio' => '1.7']
                    ])->label(AmosNews::t('amosnews', '#image_field'))->hint(AmosNews::t('amosnews', '#image_field_hint'))
                ?>
            </div>

            <div class="col-xs-12 attachment-section nop">
                <div class="col-xs-12">
                    <?= Html::tag('h2', AmosNews::t('amosnews', '#attachments_title')) ?>
                    <?=
                    $form->field($model, 'attachments')->widget(AttachmentsInput::classname(),
                        [
                            'options' => [// Options of the Kartik's FileInput widget
                                'multiple' => true, // If you want to allow multiple upload, default to false
                            ],
                            'pluginOptions' => [// Plugin options of the Kartik's FileInput widget
                                'maxFileCount' => 100, // Client max files
                                'showPreview' => false
                            ]
                        ])->label(AmosNews::t('amosnews', '#attachments_field'))->hint(AmosNews::t('amosnews',
                        '#attachments_field_hint'))
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
    </div>

    <div class="row">

        <?php
        $showReceiverSection = false;

        $moduleCwh           = \Yii::$app->getModule('cwh');
        isset($moduleCwh) ? $showReceiverSection = true : null;
        isset($moduleCwh) ? $scope               = $moduleCwh->getCwhScope() : null;

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

        $moduleTag           = \Yii::$app->getModule('tag');
        isset($moduleTag) ? $showReceiverSection = true : null;

        if ($showReceiverSection) :
            ?>

            <div class="col-xs-12">
                <?=
                Html::tag('h2', AmosNews::t('amosnews', '#settings_receiver_title'), ['class' => 'subtitle-form'])
                ?>
                <div class="col-xs-12 receiver-section">
                    <?=
                    \open20\amos\cwh\widgets\DestinatariPlusTagWidget::widget([
                        'model' => $model,
                    ]);
                    ?>
                </div>

                <?php
                $publish_enabled = \Yii::$app->user->can('NEWS_PUBLISHER_FRONTEND') && Yii::$app->getModule('news')->params['publication_always_enabled'];

                $publish_enabled = $publish_enabled ? $publish_enabled : \Yii::$app->user->can('NEWS_PUBLISHER_FRONTEND') && empty($scope) && !$pubblicatedForCommunity;

                if ($publish_enabled) {
                    if (Yii::$app->getModule('news')->params['site_publish_enabled'] || Yii::$app->getModule('news')->params['site_featured_enabled']) {
                        ?> <div class="col-xs-12 receiver-section">
                            <div class="row">
                                <?php if (Yii::$app->getModule('news')->params['site_publish_enabled']) { ?>

                                    <h3 class="subtitle-section-form"><?= AmosNews::t('amosnews', "Pubblication on the portal mode") ?>
                                        <em>(<?= AmosNews::t('amosnews', "Choose if you want to publish the news also on the portal") ?>)</em>
                                    </h3>
                                    <?php
                                    $primoPiano = '<div class="col-md-6 col-xs-12">'
                                        .$form->field($model, 'primo_piano')->widget(Select::className(),
                                            [
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
                                            ]).
                                        '</div>';
                                    echo $primoPiano;
                                }

                                if (Yii::$app->getModule('news')->params['site_featured_enabled']) {
                                    $inEvidenza = '<div class="col-md-6 col-xs-12">'
                                        .$form->field($model, 'in_evidenza')->widget(Select::className(),
                                            [
                                                'auto_fill' => true,
                                                'data' => [
                                                    '0' => AmosNews::t('amosnews', 'No'),
                                                    '1' => AmosNews::t('amosnews', 'Si')
                                                ],
                                                'options' => [
                                                    'prompt' => AmosNews::t('amosnews', 'Seleziona'),
                                                    'disabled' => ($model->primo_piano == 1 ? false : true)
                                                ]
                                            ])
                                        .'</div>';
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

        <div class="col-xs-12 note_asterisk">
            <span><?= AmosNews::t('amosnews', '#required_field') ?></span>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12">
            <?php
            $moduleNews      = \Yii::$app->getModule(AmosNews::getModuleName());
            $publicationDate = ($moduleNews->hidePubblicationDate == false) ?
                Html::tag('div',
                    $form->field($model, 'data_pubblicazione')->widget(DateControl::className(),
                        [
                            'type' => DateControl::FORMAT_DATE
                        ])->hint(AmosNews::t('amosnews', '#start_publication_date_hint'))
                    , ['class' => 'col-md-4 col-xs-12']).
                Html::tag('div',
                    $form->field($model, 'data_rimozione')->widget(DateControl::className(),
                        [
                            'type' => DateControl::FORMAT_DATE
                        ])->hint(AmosNews::t('amosnews', '#end_publication_date_hint'))
                    , ['class' => 'col-md-4 col-xs-12']) : '';

            if ($model->isNewRecord) { //default enable comment
                $model->comments_enabled = $moduleNews->defaultEnableComments;
            }

            $enableComments = Html::tag('div',
                $form->field($model, 'comments_enabled')->inline()->radioList(
                    [
                        '1' => AmosNews::t('amosnews', '#comments_ok'),
                        '0' => AmosNews::t('amosnews', '#comments_no')
                    ], ['class' => 'comment-choice'])
                , ['class' => 'col-md-4 col-xs-12']);
            ?>

            <?=
            AccordionWidget::widget([
                'items' => [
                    [
                        'header' => AmosNews::t('amosnews', '#settings_optional'),
                        'content' => $publicationDate.$enableComments.'<div class="clearfix"></div>',
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

            <?php
            $moduleSeo      = \Yii::$app->getModule('seo');
            if (isset($moduleSeo)) :
                ?>
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
                    'options' =>  Yii::$app->user->can('ADMIN') ? [] : ['style' => 'display:none;'],
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
        ?>


        <?=
        WorkflowTransitionButtonsWidget::widget([
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
            'draftButtons' => [
                News::NEWS_WORKFLOW_STATUS_DAVALIDARE => [
                    'button' => Html::submitButton(AmosNews::t('amosnews', 'Salva'), ['class' => 'btn btn-workflow']),
                    'description' => 'le modifiche e mantieni la notizia in "richiesta di pubblicazione"'
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
            ]
        ]);
        ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
