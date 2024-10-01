<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/amos/podcast/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use yii\redactor\widgets\Redactor;
use yii\helpers\Inflector;
use amos\podcast\Module;

/**
 * @var yii\web\View $this
 * @var amos\podcast\models\PodcastEpisode $model
 * @var yii\widgets\ActiveForm $form
 */

$moduleCwh = \Yii::$app->getModule('cwh');
isset($moduleCwh) ? $showReceiverSection = true : null;
isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;
?>
<div class="podcast-episode-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'podcast-episode_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); 
    ?>

    <?php
    echo \open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => \amos\podcast\models\PodcastEpisode::PODCASTEP_WORKFLOW,
        'classDivIcon' => '',
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => true
    ]);
    ?>
    <div class="row">

        <div class="col-md-8">
            <?= $form->field($model, 'podcast_id')->hiddenInput()->label(false); ?>
            <h3><strong><?= \amos\podcast\Module::t('amospodcast', "Podcast: ") ?></strong><?= $podcast->title ?>
            </h3>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?><!-- image_description text -->
            <?= $form->field($model, 'duration')->textInput(['maxlength' => true]) ?><!-- image_description text -->
            <?= $form->field($model, 'image_description')->textarea(['rows' => 5]);
            ?><!-- abstract text -->
            <?= $form->field($model, 'abstract')->textarea(['rows' => 5])
            ?><!-- description text -->
            <?= $form->field($model, 'description')->textarea(['rows' => 5]);
            ?><!-- main_player_id integer -->
            <?php echo $form->field($model, 'main_player_id')->widget(
                \kartik\select2\Select2::className(),
                [
                    'data' => \amos\podcast\models\PodcastEpisode::getPodcastTypeListLabel(),
                    'options' => ['placeholder' => Module::t('amospodcast', "Select...")]
                ]
            )->label(Module::t('amospodcast', "Quale servizio vuoi esporre come principale?")) ?>
            <?= $form->field($model, 'url_spreaker')->textInput(['maxlength' => true]) ?>
            <div>

                <?php
                $moduleTag = \Yii::$app->getModule('tag');
                //     isset($moduleTag) ? $showReceiverSection = true : null;

                if (isset($moduleTag)) { ?>
                    <div>
                        <?=
                        Html::tag('h2', Module::t('amospodcast', '#tags_title'), ['class' => 'subtitle-form'])
                        ?>
                        <div class="receiver-section">
                            <?php
                            echo \open20\amos\cwh\widgets\DestinatariPlusTagWidget::widget([
                                'model' => $model,
                                'moduleCwh' => $moduleCwh,
                                'scope' => $scope
                            ]);
                            ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="m-t-30">
                    <?php if (\Yii::$app->getModule('seo')) : ?>
                        <?=
                        \open20\amos\core\forms\AccordionWidget::widget([
                            'items' => [
                                [
                                    'header' => Module::t('amospodcast', 'Impostazioni seo'),
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

            </div>
        </div>
        <div class="col-md-4 m-t-30">
            <div>
                <?=
                $form->field($model, 'mainImage')->widget(
                    \open20\amos\attachments\components\CropInput::classname(),
                    [
                        'jcropOptions' => ['aspectRatio' => '1.7', 'placeholder' => Module::t('amosevents', 'Per una visualizzazione ottimale, carica un\'immagine rettangolare 1920x1080 pixel.')],
                        'options' => [
                            'placeholder' => Module::t('amosevents', 'Per una visualizzazione ottimale, carica un\'immagine rettangolare 1920x1080 pixel.')
                        ]
                    ]
                )->label(Module::t('amosevents', "Immagine principale del podcast"))
                ?>
            </div>
            <div class="attachment-section" style="padding:12px">
                <h3><?= Module::t('amospodcast', "File transcript") ?></h3>
                <?=
                $form->field($model, 'attachments')->widget(
                    \open20\amos\attachments\components\AttachmentsInput::classname(),
                    [
                        'options' => [ // Options of the Kartik's FileInput widget
                            'multiple' => true, // If you want to allow multiple upload, default to false
                        ],
                        'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                            'maxFileCount' => 100, // Client max files
                            'showPreview' => false
                        ]
                    ]
                )->label(Module::t('amosnews', '#attachments_field'))
                ?>
                <?=
                \open20\amos\attachments\components\AttachmentsList::widget([
                    'model' => $model,
                    'attribute' => 'attachments'
                ])
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
   


<div class="row">
        <?php
        echo
        \open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget::widget([
            'form' => $form,
            'model' => $model,
            'workflowId' => \amos\podcast\models\PodcastEpisode::PODCASTEP_WORKFLOW,
            'viewWidgetOnNewRecord' => true,
            //'closeSaveButtonWidget' => CloseSaveButtonWidget::widget($config),
            'closeButton' => Html::a(
                Module::t('amospodcast', 'Annulla'),
                '/podcast/podcast/update?id=' . $model->podcast_id,
                ['class' => 'btn btn-secondary']
            ),
            'initialStatusName' => "DRAFT",
            'initialStatus' => \amos\podcast\models\PodcastEpisode::PODCASTEP_WORKFLOW_STATUS_DRAFT,
            'statusToRender' => [
                \amos\podcast\models\PodcastEpisode::PODCASTEP_WORKFLOW_STATUS_DRAFT => 'Modifica in corso'
            ],
            //gli utenti validatore/facilitatore o ADMIN possono sempre salvare la news => parametro a false
            //altrimenti se stato VALIDATO => pulsante salva nascosto
            //'hideSaveDraftStatus' => $statusToRenderToHide['hideDraftStatus'],
            'additionalButtons' => [
                //                'default' => [
                //                    [
                //                        'button' => $buttonSalvaAndUpload,
                //                        'description' => '',
                //                    ],
                //                ]
            ],
            'draftButtons' => [
                'default' => [
                    'button' => Html::submitButton(
                        Module::t('amospodcast', 'salva'),
                        ['class' => 'btn btn-workflow']
                    ),
                    'description' => Module::t(
                        'amospodcast',
                        'potrai richiedere la pubblicazione in seguito'
                    ),
                ]
            ],
        ]);
        ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
