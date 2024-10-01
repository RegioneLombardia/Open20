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
 * @var amos\podcast\models\Podcast $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="podcast-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'podcast_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <?php
    echo \open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => \amos\podcast\models\Podcast::PODCAST_WORKFLOW,
        'classDivIcon' => '',
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => true
    ]);
    ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-6"><!-- title string -->
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?><!-- description text -->
            </div>
            <div class="col-xs-6">
                <?= $form->field($model, 'podcast_category_id')->widget(Select::classname(), [
                    'data' => ArrayHelper::map(\amos\podcast\models\PodcastCategory::find()->asArray()->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'id' => 'PodcastCategory0' . $fid,
                        'multiple' => false,
                        'placeholder' => 'Seleziona ...',
                        'class' => 'dynamicCreation' . $append,
                        'data-model' => 'podcast_category',
                        'data-field' => 'name',
                        'data-module' => 'amospodcast',
                        'data-entity' => 'podcast-category',
                        'data-toggle' => 'tooltip'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(\amos\podcast\Module::t('amospodcast', 'Category')) ?>
            </div>
            <div class="col-xs-12">
                <?= $form->field($model, 'description')->textarea(['rows' => 5]);
                ?><!-- image_description text -->
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-6">
                        <?= $form->field($model, 'image_description')->textarea(['rows' => 5]) ?>
                    </div>
                    <div class="col-xs-6">
                        <?=
                        $form->field($model, 'mainImage')->widget(
                            \open20\amos\attachments\components\CropInput::classname(),
                            [
                                'jcropOptions' => ['aspectRatio' => '1.7', 'placeholder' => Module::t('amosevents', 'Per una visualizzazione ottimale, carica un\'immagine rettangolare 1920x1080 pixel.')],
                                'options' => [
                                    'placeholder' => Module::t('amosevents', 'Per una visualizzazione ottimale, carica un\'immagine rettangolare 1920x1080 pixel.')
                                ]
                            ]
                        )->label(Module::t('amosevents', "Immagine principale dell'evento"))
                        ?>
                    </div>
                </div>
            </div>

            <?php if (!$model->isNewRecord) { ?>
                <div class="col-xs-12">
                    <h3><?= Module::t('amospodcast', "Episodes") ?></h3>

                    <?= \yii\helpers\Html::a(Module::t('amospodcast', "Add episode"), ['/podcast/podcast-episode/create', 'id' => $model->id], [
                        'class' => 'btn btn-primary'
                    ]) ?>
                    <?= \open20\amos\core\views\AmosGridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            'title',
                            [
                                'attribute' => 'workflowStatus.label',
                                'label' => Module::t('amospodcast', "Stato"),
                            ],
                            [
                                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                                'controller' => 'podcast-episode'
                            ]
                        ]
                    ]) ?>
                </div>
            <?php } else { ?>
                <div class="col-xs-12">
                    <h3><?= Module::t('amospodcast', "Episodi")?></h3>
                    <p class="alert alert-danger"><?=  Module::t('amospodcast', "Sarà possibile inserire gli episodi dopo aver salvato il Podcast") ?></p>
                </div>
            <?php }?>

            <div class="col-xs-12 nop">
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

            <?php
            echo
            \open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget::widget([
                'form' => $form,
                'model' => $model,
                'workflowId' => \amos\podcast\models\Podcast::PODCAST_WORKFLOW,
                'viewWidgetOnNewRecord' => true,
                //'closeSaveButtonWidget' => CloseSaveButtonWidget::widget($config),
                'closeButton' => Html::a(Module::t('amospodcast', 'Annulla'),
                    Yii::$app->session->get('previousUrl'), ['class' => 'btn btn-secondary']),
                'initialStatusName' => "DRAFT",
                'initialStatus' => \amos\podcast\models\Podcast::PODCAST_WORKFLOW_STATUS_DRAFT,
                'statusToRender' => [
                    \amos\podcast\models\Podcast::PODCAST_WORKFLOW_STATUS_DRAFT => 'Modifica in corso'
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
                        'button' => Html::submitButton(Module::t('amospodcast', 'salva'),
                            ['class' => 'btn btn-workflow']),
                        'description' => Module::t('amospodcast',
                            'potrai richiedere la pubblicazione in seguito'),
                    ]
                ],
            ]);
            ?>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
    <div class="clearfix"></div>

</div>
