<?php

use amos\sitemanagement\Module;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\helpers\Html;
use kartik\select2\Select2;
use open20\amos\attachments\components\CropInput;
use yii\helpers\VarDumper;
use yii\helpers\HtmlPurifier;

/**
 * @var yii\web\View $this
 * @var \amos\sitemanagement\models\SiteManagementSliderElem $model
 * @var yii\widgets\ActiveForm $form
 */
$module = \Yii::$app->getModule('sitemanagement');
$enableUploadVideoSlider = $module->enableUploadVideoSlider;
$enableInstagramVideoSlider = $module->enableInstagramVideoSlider;
$enableTextSlider = $module->enableTextSlider;
$enableFieldForCms = $module->enableFieldForCms;
$secImagesFields = $module->secondaryImagesFieldListConfiguration;
$secVideosFields = $module->secondaryVideosFieldListConfiguration;
$videoType = \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_VIDEO;
$instagramVideoType = \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_INSTAGRAM_VIDEO;
$js = <<<JS

     if($('#type-elem').val() == $videoType){
         $('#url-video').show();
         $('.img-profile').hide();
         if(($('#url-video-1 input').val().length) !== 0){
            $('#url-video-1').show();
            $('#type-of-video input[value=0]').prop("checked", true);
        } else {
           $('#path-video').show();
           $('#type-of-video input[value=1]').prop("checked", true);
        }
     } else if($('#type-elem').val() == $instagramVideoType){
         $('#instagram-video-url').show();
         $('#url-video').hide();
         $('.img-profile').hide();
         /*if(($('#url-video-1 input').val().length) !== 0){
            $('#url-video-1').show();
            $('#type-of-video input[value=0]').prop("checked", true);
        } else {
           $('#path-video').show();
           $('#type-of-video input[value=1]').prop("checked", true);
        }*/
     }
     
     
    $('#type-elem').on('select2:select', function(){
        if($(this).val() == $videoType){
            $('#url-video').show();
            $('.img-profile').hide();
        } else {
            $('#url-video').hide();
            $('.img-profile').show();
        }
    });

$('#type-of-video input[type=radio]').click(function(){
    if($(this).val() == 1){
        $('#path-video').show();
        $('#url-video-1').hide();
        $('#url-video-1 input').val('');
    }
    else {
        $('#url-video-1').show();
        $('#path-video').hide();
         $('#path-video select').val('');

    }
});
JS;

$this->registerJs($js);
$tipoElemento = $_GET['slider_type'];
?>

<div class="site-management-slider-elem-form row">

    <?php $form = ActiveForm::begin(); ?>
    <?php $this->beginBlock('generale'); ?>

    <div class="col-sm-12 m-t-10">
        <strong><?= \Yii::t('app', 'Galleria immagini') . ': ' ?></strong>
        <?= $slider->title ?>
    </div>

    <div class="col-lg-6 col-sm-6">
        <?php if ($tipoElemento == 1) { ?>
            <?= $form->field($model, 'title')->label(Module::t('amosevents', 'Didascalia'))->textInput(['placeholder' => Module::t('amosevents', 'Scrivi una didascalia per la tua immagine. Usa un massimo di 50 caratteri'), 'maxlength' => true]) ?>
        <?php } else if ($tipoElemento == 2 || $tipoElemento == 3) { ?>
            <?= $form->field($model, 'title')->label(Module::t('amosevents', 'Titolo del video'))->textInput(['placeholder' => Module::t('amosevents', 'Scrivi un titolo per il tuo video. Usa un massimo di 50 caratteri'), 'maxlength' => true]) ?>
        <?php } else { ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        <?php } ?>
    </div>

    <?php
    $displaynone = '';
    if (!empty($model->type)) {
        $displaynone = 'display:none;';
    }
    ?>

    <div class="col-lg-3 col-sm-3" style="<?= $displaynone ?>">
        <?php
        $data = [];

        if ($onlyImages) {
            $data = [
                \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_IMG => Module::t('amossitemanagement',
                        'Immagine')
            ];
        } else if ($onlyVideos) {
            $data = [
                \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_VIDEO => Module::t('amossitemanagement',
                        'Video')
            ];
        } else if ($onlyInstagramVideos) {
            $data = [
                \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_INSTAGRAM_VIDEO => Module::t('amossitemanagement',
                        'Instagram Video')
            ];
        } else {
            $data = [
                \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_IMG => Module::t('amossitemanagement',
                        'Immagine'),
                \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_VIDEO => Module::t('amossitemanagement',
                        'Video'),
                \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_INSTAGRAM_VIDEO => Module::t('amossitemanagement',
                        'Instagram Video'),
            ];
        }
        ?>
        <?=
        $form->field($model, 'type')->widget(Select2::className(),
                [
                    'options' => [
                        'id' => 'type-elem',
                        'placeholder' => Module::t('amossitemanagement', 'Select...')
                    ],
                    'data' => $data,
//                'pluginOptions' =>[
//                        'allowClear' => true
//                ]
                ])
        ?>
    </div>

    <div class="col-lg-3 col-sm-3">
        <?= $form->field($model, 'order')->textInput() ?>
    </div>


    <div class="col-sm-6 col-md-6 col-xs-12">
        <div class="img-profile">
            <?php if (!empty($useCrop) && $useCrop == true) { ?>
                <?=
                $form->field($model, 'fileImage')->widget(CropInput::classname(),
                        [
                            'options' => [// Options of the Kartik's FileInput widget
                                'accept' => "image/*"
                            ],
                            'jcropOptions' => [
                                'aspectRatio' => (!empty($ratioCrop) ? $ratioCrop : '1.7'),
                                'maxFileCount' => 1, // Client max files,
                                'allowedPreviewTypes' => ['image'],
                                'showPreview' => true,
                            ],
                        ])->label(Module::t('amossitemanagement', 'Image'));
                ?>
            <?php } else { ?>
                <?=
                $form->field($model, 'fileImage')->widget(\open20\amos\attachments\components\AttachmentsInput::classname(),
                        [
                            'options' => [// Options of the Kartik's FileInput widget
                                'accept' => "image/*"
                            ],
                            'pluginOptions' => [// Plugin options of the Kartik's FileInput widget
                                'maxFileCount' => 1, // Client max files,
                                'allowedPreviewTypes' => ['image'],
                                'showPreview' => true,
                            ]
                        ])->label(Module::t('amossitemanagement', 'Image'))
                ?>
            <?php } ?>
        </div>
    </div>


    <div id='url-video' class="col-lg-12 col-sm-12" hidden>
        <?php if ($enableUploadVideoSlider) { ?>
            <div id="type-of-video" class="col-lg-6 col-sm-12">
                <?=
                $form->field($model, 'typeOfvideo')->radioList([0 => 'Video da Url', 1 => 'Video da file'])->label(Module::t('amossitemanagement',
                                'Tipo di video'))
                ?>
            </div>
            <div id="url-video-1" class="col-lg-6 col-sm-12" hidden>
                <?=
                $form->field($model, 'url_video')->textInput(['maxlength' => true])->label(Module::t('amossitemanagement',
                                'URL video'))
                ?>
            </div>
            <div id="path-video" class="col-lg-6 col-sm-12" hidden>
                <?=
                $form->field($model, 'path_video')->widget(Select2::className(),
                        [
                            'data' => $files,
                            'options' => ['placeholder' => Module::t('amossitemanagement', 'Select...')]
                        ])->label(Module::t('amossitemanagement', ''))
                ?>
            </div>
        <?php } else { ?>
            <div style="display:none;">
                <?php $model->typeOfvideo = 1; ?>
                <?=
                $form->field($model, 'typeOfvideo')->radioList([0 => 'Video da Url', 1 => 'Video da file'])->label(Module::t('amossitemanagement',
                                'Tipo di video'))
                ?>
            </div>
            <div id="url-video-1" class="col-lg-6 col-sm-12 nop">
                <?=
                $form->field($model, 'url_video')->textInput(['placeholder' => Module::t('amosadmin', 'Inserisci qui l\'URL del video YouTube'), 'maxlength' => true])->label(Module::t('amossitemanagement',
                                'URL video'))
                ?>
            </div>
        <?php } ?>
    </div>

    <?php
// SE TIPO IMMAGINE
    if ($tipoElemento == 1) {
        ?>
        <div id='instagram-video-url' class="col-lg-12 col-sm-12" hidden>
            <?php if ($enableInstagramVideoSlider) { ?>
                <div class="col-lg-6 col-sm-12 nop">
                    <?=
                    $form->field($model, 'instagram_url_video')->textInput(
                            [
                                'placeholder' => Module::t('amossitemanagement', 'Inserisci l\'URL del video Instagram'),
                            ]
                    );
                    ?>
                </div>
                <div class="col-lg-3 col-sm-3">
                    <?=
                    $form->field($model, 'instagram_video_caption')->widget(Select2::className(),
                            [
                                'data' => Html::getBooleanFieldsValues(),
                                'hideSearch' => true
                            ]
                    )->label(Module::t('amossitemanagement', 'Includi didascalia'));
                    ?>
                </div>
            <?php } ?>
        </div>

        <?php if ($enableTextSlider) {
            ?>
            <hr>

            <?php
            $getTitle = HtmlPurifier::process(Yii::$app->request->get('slider_title'));
            if (!empty($getTitle)):
                if (isset($secImagesFields['link']['render']) && $secImagesFields['link']['render']):
                    ?>
                    <div class="col-lg-6 col-sm-12">
                        <?= $form->field($model, 'link')->textInput() ?>
                    </div>
                    <?php
                endif;
            endif;
            ?>

            <?php
            if (isset($secImagesFields['text_position']['render']) && $secImagesFields['text_position']['render']):
                ?>
                <div class="col-lg-6 col-sm-12">
                    <?=
                    $form->field($model, 'text_position')->widget(Select2::className(),
                            [
                                'data' => $model->getAllTextPositionLabel(),
                                'options' => ['placeholder' => Module::t('amossitemanagement', 'Select...')]
                            ])
                    ?>
                </div>
                <?php
            endif;
            ?>

            <?php
            if (isset($secImagesFields['description']['render']) && $secImagesFields['description']['render']):
                ?>
                <div class="col-lg-12 col-sm-12">
                    <?=
                    $form->field($model, 'description')->widget(\open20\amos\core\forms\TextEditorWidget::className(),
                            [
                                'clientOptions' => [
                                    'lang' => substr(Yii::$app->language, 0, 2)
                                ]
                            ])
                    ?>

                    <?php
                endif;
                ?>
            </div>
        <?php }
        ?>
        <?php
// SE  di tipo VIDEO
    } else if ($tipoElemento == 2) {

        if ($enableTextSlider) {
            ?>
            <hr>
            <?php
            if (isset($secVideosFields['link']['render']) && $secVideosFields['link']['render']):
                ?>
                <div class="col-lg-6 col-sm-12">
                    <?= $form->field($model, 'link')->textInput() ?>
                </div>
                <?php
            endif;
            ?>

            <?php
            if (isset($secVideosFields['text_position']['render']) && $secVideosFields['text_position']['render']):
                ?>
                <div class="col-lg-6 col-sm-12">
                    <?=
                    $form->field($model, 'text_position')->widget(Select2::className(),
                            [
                                'data' => $model->getAllTextPositionLabel(),
                                'options' => ['placeholder' => Module::t('amossitemanagement', 'Select...')]
                            ])
                    ?>
                </div>
                <?php
            endif;
            ?>

            <?php
            if (isset($secVideosFields['description']['render']) && $secVideosFields['description']['render']):
                ?>
                <div class="col-lg-12 col-sm-12">
                    <?=
                    $form->field($model, 'description')->widget(\open20\amos\core\forms\TextEditorWidget::className(),
                            [
                                'clientOptions' => [
                                    'lang' => substr(Yii::$app->language, 0, 2)
                                ]
                            ])
                    ?>

                    <?php
                endif;
                ?>
            </div>
            <?php
        }

// nel caso non sia stata passata la tipologia di slider non � possibile gestire la casistica e vengono proposti tutti i campi
// come era prima... 
    } else {
        if ($enableFieldForCms) {
            ?>           
            <hr>
            <div class="col-lg-12">

                <h4><?= Module::t('amossitemanagement', 'Campi per lo slider del CMS') ?></h4>
            </div>
            <div class="col-lg-12">

                <?=
                $form->field($model, 'description')->widget(\open20\amos\core\forms\TextEditorWidget::className(),
                        [
                            'clientOptions' => [
                                'lang' => substr(Yii::$app->language, 0, 2)
                            ]
                        ])
                ?>
            </div>
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'link')->textInput() ?>
            </div>
            <div class="col-lg-6 col-sm-12">
                <?= $form->field($model, 'cta_label')->textInput() ?>
            </div>
            <div class="col-lg-6 col-sm-12">
                <?= $form->field($model, 'target')->dropDownList([0 => Module::t('amossitemanagement', 'NO'), 1 => Module::t('amossitemanagement', 'SI')]) ?>
            </div>
            <div class="col-lg-6 col-sm-12">
                <?= $form->field($model, 'btn_icon')->textInput() ?>
            </div>
            <div class="col-lg-6 col-sm-12">
                <?= $form->field($model, 'category')->textInput() ?>
            </div>

            <?php
        } else if ($enableTextSlider) {
            ?>
            <hr>
            <div class="col-lg-6 col-sm-12">
                <?= $form->field($model, 'link')->textInput() ?>
            </div>
            <div class="col-lg-6 col-sm-12">
                <?=
                $form->field($model, 'text_position')->widget(Select2::className(),
                        [
                            'data' => $model->getAllTextPositionLabel(),
                            'options' => ['placeholder' => Module::t('amossitemanagement', 'Select...')]
                        ])
                ?>
            </div>
            <div class="col-lg-12 col-sm-12">
                <?=
                $form->field($model, 'description')->widget(\open20\amos\core\forms\TextEditorWidget::className(),
                        [
                            'clientOptions' => [
                                'lang' => substr(Yii::$app->language, 0, 2)
                            ]
                        ])
                ?>
            </div>
            <?php
        }
    }
    ?>

    <div class="clearfix"></div>
    <?php $this->endBlock('generale'); ?>

    <?php
    $itemsTab[] = [
        'label' => Module::t('amossitemanagement', 'generale'),
        'content' => $this->blocks['generale'],
    ];
    ?>

    <?=
    Tabs::widget(
            [
                'encodeLabels' => false,
                'items' => $itemsTab
            ]
    );
    ?>
    <div class="col-xs-12">
        <?=
        CloseSaveButtonWidget::widget([
            'model' => $model,
            'urlClose' => ((\Yii::$app->request->referrer && strpos(\Yii::$app->request->referrer, 'create') === false) ? \Yii::$app->request->referrer : \yii\helpers\Url::previous()),
        ]);
        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
