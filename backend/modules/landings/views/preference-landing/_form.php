<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/landings/views
 */

use open20\amos\core\helpers\Html;
use yii\bootstrap4\ActiveForm;
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
use backend\modules\landings\Module;
use open20\amos\layout\utility\AmosIconsBi;
use open20\amos\admin\models\UserProfile;

use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;

$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
/**
 * @var yii\web\View $this
 * @var backend\modules\landings\models\PreferenceLanding $model
 * @var yii\widgets\ActiveForm $form
 * @var array $sondaggiChoices
 * @var \yii\data\ActiveDataProvider $dataProviderLinks
 */

$js = <<<JS
$('#url-landing').keyup(function(){
     var pressed = $(this).val();
     pressed = pressed.toLowerCase();
    $(this).val(pressed);
});

JS;

$jsSeo = <<<JS
      $('#landing-title').change(function(){
        var title = $(this).val();
                $.ajax({
                    url: '/seo/api/prettyurl',
                    type: 'POST',
                    data: {
                        slug: title
                    },
                    success: function (data) {
                        $('#url-landing').val(data['pretty_url']);
                    }
                });    
    });  

JS;

$jsSaveData = <<<JS
var changedValues = false;
$('.save-all-data').click(function (e) {
    e.preventDefault();
    $('#save_and_redirect_to').val($(this).attr('href'));
    // console.log(changedValues);
    if (changedValues) {
        $('#confirm-before-save').modal('show');
    } else {
        window.location.href = $(this).attr('href');
    }
});

$('#confirm-before-save').on('hidden.bs.modal', function () {
    $('#save_and_redirect_to').val('');
});

$('#annulla-save').click(function (e) {
    e.preventDefault();
    $('#save_and_redirect_to').val('');
    $('#confirm-before-save').modal('hide');
});

$('.check-change-value').on('change', function (){
     changedValues = true;
     console.log(changedValues);
});

JS;

$tinyMCECallback = <<< JS
    function(ed) {
     ed.on('input', function () {
              changedValues = true;
       });
   }
JS;

$this->registerJs($js);
$this->registerJs($jsSeo);
$this->registerJs($jsSaveData);

?>



<div class="preference-landing-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'preference-landing_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>

    <?php \yii\bootstrap4\Modal::begin([
        'id' => 'confirm-before-save',
        'size' => \yii\bootstrap4\Modal::SIZE_LARGE,
        'title' => 'Avviso salvataggio',

    ]); ?>

    <p><?= Module::t('preferencelandings', "Verranno salvate le modifiche apportate alla scheda, confermi?") ?></p>
    <div class="mt-3">
        <?= Html::a(Module::t('preferencelandings', "Annulla"), '', [
                'class' => 'btn btn-secondary btn-sm',
                'id' => 'annulla-save'
            ]
        ); ?>
        <?= Html::submitButton(Module::t('preferencelandings', "Conferma"), [
                'class' => 'btn btn-primary btn-sm',
                'id' => 'confirm-save'
            ]
        ); ?>
    </div>
    <?php \yii\bootstrap4\Modal::end(); ?>


    <?php echo $form->errorSummary($model); ?>
    <?php echo Html::hiddenInput('save_and_redirect_to', null, ['id' => 'save_and_redirect_to']) ?>

    <div class="row">
        <div class="col-md-12">
            <!-------------------- CONFIGURAZIONI LANDING ------------------------------------->
            <div class="affix-parent">


                <div class="mb-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
                    <div class="mr-2">
                        <svg class="icon">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_web"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold ">
                        <?= Module::t('preferencelandings', "Configura la landing page") ?>
                    </h5>
                </div>

                <div class="row">
                    <div class="col-md-3 mr-auto text-muted">
                        <p><?= Module::t('preferencelandings', "Modifica l'URL per ottimizzarlo per i motori di ricerca. Usa URL brevi, leggibili e che contengano le keywords della tua pagina.") ?></p>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'id' => 'landing-title', 'class' => 'check-change-value']) ?>
                                <!-- description text -->
                            </div>

                            <div class="col-md-6">
                                <?php
                                $user_pc_structure = UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();

                                if (null != $user_pc_structure->pc_structure_id) :

                                    echo $form->field($model, 'preference_structure_id')->hiddenInput(['value' => $user_pc_structure->pc_structure_id])->label(false);

                                else :

                                    echo $form->field($model, 'preference_structure_id')->widget(Select::classname(), [
                                        'data' => ArrayHelper::map(\backend\modules\operators\models\PreferenceStructure::find()->all(), 'id', 'name'),
                                        'language' => substr(Yii::$app->language, 0, 2),
                                        'options' => [
                                            'id' => 'PreferenceStructure0' . $fid,
                                            'multiple' => false,
                                            'placeholder' => 'Seleziona ...',
                                            'class' => 'check-change-value'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],

                                    ])->label(Module::t('preferencelandings', "Struttura"));

                                endif;
                                ?>
                            </div>
                        </div>

                        <p><strong><?= Module::t('preferencelandings', "Modifica l'URL della landing page: ") ?></strong>
                        </p>
                        <div class="row">
                            <div class="col-lg mb-3">
                                <?= \Yii::$app->params['platform']['frontendUrl'] . '/it/' ?>
                            </div>
                            <div class="col-lg mb-3">
                                <?= $form->field($model, 'url')
                                    ->textInput([
                                        'maxlength' => 80,
                                        'id' => 'url-landing',
                                        'class' => 'check-change-value'
                                    ])
                                    ->label(false)->hint('Max 80 caratteri'); ?>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-3 mr-auto text-muted">
                        <p><?= Module::t('preferencelandings', "Seleziona il template della pagina per la tua landing") ?></p>
                    </div>
                    <div class="col-md-8">
                        <div class="row variable-gutters">
                            <div class="col-md-12">
                                <div class="input-color-landing">
                                    <?php if (empty($model->luya_template_id) && count($arrayTemplates) > 0) {
                                        $i = 0;
                                        foreach ($arrayTemplates as $key => $tmp) {
                                            if ($i == 0) {
                                                $model->luya_template_id = $key;
                                            }
                                            $i++;
                                        }
                                    } ?>
                                    <?php
                                    //  echo $form->field($model, 'luya_template_id')
                                    //     ->radioList($arrayTemplates, ['id' => 'luya-template-id'])
                                    //     ->label("<strong>" . Module::t('preferencelandings', "Scegli il template") . "</strong>") 
                                    ?>


                                    <?= $form->field($model, 'luya_template_id')->widget(Select::classname(), [
                                        'data' => $arrayTemplates,
                                        'language' => substr(Yii::$app->language, 0, 2),
                                        'options' => [
                                            'id' => 'luya-template-id',
                                            'multiple' => false,
                                            'placeholder' => 'Seleziona ...',
                                            'class' => 'check-change-value'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],

                                    ])->label(Module::t('preferencelandings', "Scegli il template")); ?>
                                </div>
                            </div>
                            <div class="d-none">

                            </div>
                        </div>

                    </div>
                </div>

                <hr class="dashed">
            </div>
            <!-------------------- IMMAGINI ------------------------------------->
            <div class="affix-parent">
                <div class="my-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
                    <div class="mr-2">
                        <svg class="icon">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_panorama"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold ">
                        <?= Module::t('preferencelandings', "Inserisci un'immagine di copertina") ?>
                    </h5>
                </div>

                <div class="row variable-gutters">
                    <div id="main-image" class="col-md-12">
                        <div>
                            <?=
                            $form->field($model, 'mainImage')->widget(
                                \open20\amos\attachments\components\CropInput::classname(),
                                [
                                    'jcropOptions' => [
                                            'aspectRatio' => '1.7'
                                        , 'placeholder' => Module::t('preferencelandings', 'Per una visualizzazione ottimale, carica un\'immagine rettangolare 1920x1080 pixel.')],
                                    'options' => [
                                        'placeholder' => Module::t('preferencelandings', 'Per una visualizzazione ottimale, carica un\'immagine rettangolare 1920x1080 pixel.')
                                        , 'class' => 'form-control form-control-file is-valid check-change-value'
                                    ]
                                ]
                            )->label(Module::t('preferencelandings', "Immagine principale dell'evento"))
                            ?>
                        </div>
                    </div>
                </div>


                <!--            <div class="row variable-gutters my-4">-->
                <!--                <div class="col-md-12">-->
                <!--                    <div>-->
                <!--                        --><?php
                                                //                        $form->field($model, 'mainImageMobile')->widget(
                                                //                            \open20\amos\attachments\components\CropInput::classname(),
                                                //                            [
                                                //                                'jcropOptions' => ['aspectRatio' => '1.0']
                                                //                            ]
                                                //                        )->label(Module::t('preferencelandings', "Immagine dell'evento per Dispositivi Mobile"))
                                                //                        
                                                ?>
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </div>-->

                <hr class="dashed">
            </div>
            <div class="affix-parent">
                <div class="my-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
                    <div class="mr-2">
                        <svg class="icon">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_poll"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold ">
                        <?= Module::t('preferencelandings', "Sondaggio") ?>
                    </h5>
                </div>

                <?php
                echo $form->field($model, 'sondaggi_id')->widget(Select::classname(), [
                    'data' => $sondaggiChoices,
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'id' => 'sondaggi_id',
                        'multiple' => false,
                        'placeholder' => 'Seleziona ...',
                        'class' => 'check-change-value'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(Module::t('preferencelandings', "Sondaggio"));
                ?>

                <hr class="dashed my-5">
            </div>

            <!-------------------- PLAYLIST YOUTUBE ------------------------------------->
            <div class="affix-parent">
                <div class="my-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
                    <div class="mr-2">
                        <svg class="icon">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_videocam"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold ">
                        <?= Module::t('preferencelandings', "Playlist Youtube") ?>
                    </h5>
                </div>

                <?= $form->field($model, 'playlist_youtube_description')->textInput(['maxlength' => true, 'class' => 'check-change-value']) ?>
                <?= $form->field($model, 'playlist_youtube_url')->textInput(['maxlength' => true, 'class' => 'check-change-value'])->hint('Url di esempio: https://www.youtube.com/playlist?list=PLO2yE_1Y2y9sRzC0CCUAijOmVhhb_1czP') ?>

                <hr class="dashed my-5">
            </div>

            <!-------------------- CONTENUTI ------------------------------------->
            <div class="affix-parent">
                <div class="my-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
                    <div class="mr-2">
                        <svg class="icon">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_description"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold ">
                        <?= Module::t('preferencelandings', "Crea contenuti") ?>
                    </h5>
                </div>
                <?= $form->field($model, 'schedule')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
                    'options' => ['placeholder' => Module::t('preferencelandings', 'Scrivi una breve presentazione')],
                    'clientOptions' => [
                        'setup' => new \yii\web\JsExpression($tinyMCECallback),
                    ]
                ]);
                ?>
                <!-- image_slider_id integer -->

                <?= $form->field($model, 'description')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
                    'options' => [
                        'placeholder' => Module::t('preferencelandings', 'Scrivi una breve descrizione')
                    ],
                    'clientOptions' => [
                        'setup' => new \yii\web\JsExpression($tinyMCECallback),
                    ]
                ]);
                ?>
                <!-- schedule text -->
                <hr class="dashed my-5">
            </div>
            <div class="affix-parent">

                <div class="my-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
                    <div class="mr-2">
                        <svg class="icon">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_group"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold ">
                        <?= Module::t('amosevents', "Speakers") ?>
                    </h5>
                </div>

                <div>
                    <?php if (!$model->isNewRecord) { ?>
                        <?php echo Html::a(Module::t('amosevents', 'Aggiungi speaker'), [
                            '/landings/preference-landing-protagonist/create', 'idLanding' => $model->id
                        ], [
                            'class' => 'btn btn-secondary btn-sm mb-3 save-all-data'
                        ]) ?>
                        <?= \open20\amos\core\views\AmosGridView::widget(
                            [
                                'dataProvider' => $dataProviderProtagonists,
                                'columns' => [
                                    'immagine' => [
                                        'label' => Module::t('amosevents', 'Immagine'),
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            /** @var News $model */
                                            $url = '/img/img_default.jpg';
                                            if (!is_null($model->image)) {
                                                $url = $model->image->getWebUrl('table_small', false, true);
                                            }
                                            $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => Module::t('amosnews', 'Immagine della notizia')]);

                                            return Html::a($contentImage, []);
                                        }
                                    ],
                                    'name',
                                    'surname',
                                    'company',
                                    [
                                        'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                                        'controller' => 'preference-landing-protagonist',
                                        'template' => '{update}{delete}',
                                        'buttons' => [
                                            'update' => function ($url, $model) {
                                                return Html::a(AmosIconsBi::show('ic_edit'), $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => Module::t('preferencelandings', 'Modifica')], true);
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a(AmosIconsBi::show('ic_close', 'danger'), $url, [
                                                    'class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => Module::t('preferencelandings', 'Elimina'),
                                                    'data-confirm' => 'Sei sicuro di eliminare la landing?'
                                                ], true);
                                            }
                                        ]
                                    ]
                                ]
                            ]
                        ) ?>
                    <?php } else { ?>
                        <p><?= Module::t('amosevents', 'Per inserire i protagonisti è necessario salvare i dati') ?></p>
                    <?php } ?>

                </div>
                <hr class="dashed my-5">
            </div>
            <div class="affix-parent">
                <div class="my-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
                    <div class="mr-2">
                        <svg class="icon">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_image"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold ">
                        <?= Module::t('amosevents', "Immagini") ?>
                    </h5>
                </div>
                <div>
                    <?php if (!$model->isNewRecord) { ?>
                        <?= $this->render('parts/_sliders', [
                            'model' => $model,
                            'dataProviderSliderElemImage' => $dataProviderSliderElemImage,
                        ]) ?>
                    <?php } else { ?>
                        <p><?= Module::t('preferencelandings', 'Per inserire le immagini nella gallery è necessario salvare i dati.') ?></p>
                    <?php } ?>
                </div>


                <hr class="dashed my-5">
            </div>
            <div class="affix-parent">
                <div class="my-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
                    <div class="mr-2">
                        <svg class="icon">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_videocam"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold ">
                        <?= Module::t('amosevents', "Video") ?>
                    </h5>
                </div>
                <div>
                    <?php if (!$model->isNewRecord) { ?>
                        <?= $this->render('parts/_sliders_video', [
                            'model' => $model,
                            'dataProviderSliderElemVideo' => $dataProviderSliderElemVideo

                        ]) ?>
                    <?php } else { ?>
                        <p><?= Module::t('preferencelandings', 'Per inserire i video è necessario salvare i dati.') ?></p>
                    <?php } ?>
                </div>



                <hr class="dashed my-5">
            </div>
            <div class="affix-parent">
                <div class="my-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
                    <div class="mr-2">
                        <svg class="icon">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_description"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold ">
                        <?= Module::t('amosevents', "Info") ?>
                    </h5>
                </div>
                <div>
                    <?php if (!$model->isNewRecord) { ?>
                        <?= $this->render('parts/_news', [
                            'model' => $model,
                            'dataProviderNews' => $dataProviderNews
                        ]) ?>
                    <?php } else { ?>
                        <p><?= Module::t('preferencelandings', 'Per aggiungere le notizie é necessario salvare i dati.') ?></p>
                    <?php } ?>
                </div>


                <hr class="dashed my-5">
            </div>
            <div class="affix-parent" id="pos-allegati" >
                <div class="my-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
                    <div class="mr-2">
                        <svg class="icon">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_attach_file"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold ">
                        <?= Module::t('amosevents', "Allegati") ?>
                    </h5>
                </div>
                <div>
                    <?php if (!$model->isNewRecord) { ?>
                        <?= $this->render('parts/_documenti', [
                            'model' => $model,
                            'dataProviderDocumenti' => $dataProviderDocumenti
                        ]) ?>
                    <?php } else { ?>
                        <p><?= Module::t('preferencelandings', 'Per aggiungere le notizie é necessario salvare i dati.') ?></p>
                    <?php } ?>
                </div>


                <hr class="dashed my-5" id="pos-links">
            </div>

            <div class="affix-parent">
                <div class="my-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
                    <div class="mr-2">
                        <svg class="icon">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_language"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold ">
                        <?= Module::t('amosevents', "Links") ?>
                    </h5>
                </div>
                <div>
                    <?php
                    if (!$model->isNewRecord) { ?>
                        <?= $this->render('parts/_links', [
                            'model' => $model,
                            'dataProviderLinks' => $dataProviderLinks,
                        ]) ?>
                    <?php } else { ?>
                        <p><?= Module::t('preferencelandings', 'Per inserire i links è necessario salvare i dati.') ?></p>
                    <?php } ?>
                </div>


                <hr class="dashed my-5">
            </div>


            <div class="affix-parent">
                <div class="mt-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
                    <div class="mr-2">
                        <!-- <span class="mdi mdi-36px mdi-star-half-full"></span> -->
                        <svg class="icon">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_star-half-full"></use>
                        </svg>
                    </div>

                    <h5 class="font-weight-bold ">
                        <?= Module::t('amosevents', "Rating") ?>
                    </h5>
                </div>
                <div>
                    <?= $form->field($model, 'rating_flag')->checkbox(['id' => 'rating-flag-id', 'class' => 'check-change-value']) ?>
                </div>
                <div>
                    <?= $form->field($model, 'rating_description')->textarea(['maxlength' => true, 'class' => 'check-change-value', 'id' => 'rating-description-id']) ?>
                </div>
                <?php
                if (!$model->isNewRecord) :
                    /** @var \yii\db\ActiveQuery $query */
                    $query = \backend\modules\landings\models\PreferenceLandingRating::find();

                    $query->andWhere(['preference_landing_id' => $model->id]);
                    /** @var \yii\db\ActiveQuery $queryAverage */
                    $queryAverage = clone $query;

                    $numRatings = $query->count();

                    $sumRatings = $queryAverage->sum('rating');
                    $average = '-';
                    if ($numRatings != 0) {
                        $average = bcdiv($sumRatings, $numRatings, 1);
                    }
                ?>
                    <hr>
                    <div>
                        <h5>Risultati</h5>
                        <div>
                            <strong>Voto medio:</strong> <?= $average ?> / 5
                        </div>
                        <div>
                            <strong>Nr. partecipanti:</strong> <?= $numRatings ?>
                        </div>
                    </div>
                <?php
                endif;
                ?>
            </div>

            <div class="col-md-12 mt-5 d-flex">
                <?= Html::a(
                    Module::t('preferencelandings', "Annulla"),
                    '/landings/preference-landing/index',
                    [
                        'class' => 'btn btn-secondary mr-auto',
                        'id' => 'annulla-save'
                    ]
                ); ?>
                <?= Html::submitButton(
                    Module::t('preferencelandings', "Salva"),
                    [
                        'class' => 'btn btn-primary ml-1 order-last',
                        'id' => 'confirm-save'
                    ]
                ); ?>
                <?php if (!$model->isNewRecord) {
                    if ($model->status == \backend\modules\landings\models\PreferenceLanding::PREFERENCE_LANDING_STATUS_DRAFT) {
                        echo Html::submitButton(
                            Module::t('preferencelandings', "Pubblica"),
                            [
                                'class' => 'btn btn-primary ml-1 order-last',
                                'name' => 'publish-action',
                                'value' => 1
                            ]
                        );
                    } else {
                        echo Html::submitButton(
                            Module::t('preferencelandings', "Togli dalla pubblicazione"),
                            [
                                'class' => 'btn btn-outline-warning ml-1 order-2',
                                'name' => 'unpublish-action',
                                'value' => 1

                            ]
                        );
                    }
                } ?>
            </div>




            <?php ActiveForm::end(); ?>
        </div>
        <div class="clearfix"></div>

    </div>
</div>