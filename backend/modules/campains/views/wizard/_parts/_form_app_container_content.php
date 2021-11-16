<?php 

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\modules\campains\Module;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\forms\TextEditorWidget;
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\models\PreferenceCampain;
use backend\modules\landings\models\PreferenceLanding;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\core\forms\editors\Select;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);


$cropChoiceId = ('crop-choice-id' . $model->id);
$cropPlaceholder = Module::t('campains', 'Carica un\'immagine ');
?>

<div class="newsletter-container-content">

    <!-- START hidden input -->
    <?= $form->field($model, 'preference_campain_id')->hiddenInput(['value'=> $model_preference_campain->id])->label(false) ?>
    <?= $form->field($model, 'id')->hiddenInput(['value'=> $model->id])->label(false) ?>
    <?= $form->field($model, 'preference_campain_section_id')->hiddenInput(['class' => "preference_campain_section", 'value'=> $model->preference_campain_section_id])->label(false) ?>
    <?= $form->field($model, 'preference_campain_container_type_id')->hiddenInput(['value'=> $model->preference_campain_container_type_id])->label(false) ?>
    <!-- END hidden input -->


    <!-- START SECTION Immagine contenuto -->
    <div class="row">
        <?php
            $idCrop = ('content-image-id' . ((!empty($model->id))? ('-' . $model->id): ''));
        ?>
        <?=
            $form->field($model, 'contentImage')->widget(
                \open20\amos\attachments\components\CropInput::classname(),
                [
                    'aspectRatioChoices' => [
                        [
                            'title' => 'Elimina Crop',
                            'value' => 'x',
                            'label' => 'X',
                        ],
                        [
                            'title' => 'Fattore di crop libero',
                            'value' => 'NaN',
                            'label' => 'Free',
                        ],
                    ],
                    'cropModalCloseButton' => false,
                    'jcropOptions' => [
                        'aspectRatio' => 'NaN', 
                        'placeholder' => $cropPlaceholder],
                    'options' => [
                        'id' => $idCrop,
                        'onclick' => "this.value=null",
                    ],
                ]
            )->label(Module::t('campains', "Inserisci l'immagine"))
        ?>
        <?php

        // \open20\amos\attachments\components\CropInput::manualValidation([
        //     'model' => $model, 
        //     'attribute' => 'contentImage', 
        //     'id' =>  ('content-image-id-' . $model->id)
        //     ]);
                   // endif;
        ?>
        <!-- <input type="file" id="content_image_path" name="content_image_path" accept="image/png, image/jpg">
        <img id="content_image_path_preview" src="#" alt="your image" /> -->
    </div>
    <!-- END SECTION Immagine contenuto -->

    <div class="row">
        <div class="col-md-12">
        <h5 class="font-weight-bold"><?= $form->field($model, 'content_title')->textInput([
                'maxlength' => "255"
            ])->hint('max 255 caratteri') ?></h5>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <?=
                $form->field($model, 'content_text')->widget(TextEditorWidget::className(), [
                    'options' => [
                        'id' => $model->id,
                    ],
                    'clientOptions' => [
                        'placeholder' => Yii::t('amosevents', 'Inserisci abstract (max 5000 char.)'),
                        'lang' => substr(Yii::$app->language, 0, 2),
                        'toolbar' => "bold italic | bullist numlist | alignleft",
                        "contextmenu" => false,
                        'forced_root_block' => false,
                    ],
                ])->label('Testo (abstract)')->hint('max 425 caratteri');
            ?>
        </div>
    </div>
    <!-- END SECTION Inserisci testo -->

    <!-- START SECTION Inserisci link -->
    <div class="row mt-5">
        <div class="col-md-12">
            <h5 class="font-weight-bold">Inserisci link</h5>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-md-12">
            <div class="radio">
                <label>
                    Link esterno alla news completa
                    <input type="radio" name="inserisci_link[]" value="content_news_link" <?= !empty($model->content_news_link) ? "checked" : '' ?>>
                </label>
            </div>
            <?= 
                $form->field($model, 'content_news_link')->textInput([
                    'placeholder' => Module::t('amosevents', 'Link esterno alla news completa'),
                    'class' => "preferencecampaincontainer-content_news_link",
                    'disabled' => $model->content_news_link == null ? true : false
                ])->label(false) 
            ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="radio">
                <label>
                    Link a landing page esistente
                    <input type="radio" name="inserisci_link[]" value="preference_landing_id" <?= !empty($model->preference_landing_id) ? "checked" : '' ?>>
                </label>
            </div>
            <?= 
                $form->field($model, 'preference_landing_id')->widget(\kartik\select2\Select2::className(),[
                    'data' => \yii\helpers\ArrayHelper::map(PreferenceLanding::find()->where(['status' => 'PUBLISHED', 'deleted_at' => null])->orderBy(['created_at' => SORT_DESC])->all(),'id', 'title'),
                    'options' => [
                        'id' => $model->isNewRecord ? "preferencecampaincontainer-preference_landing_id" : "preferencecampaincontainer-preference_landing_id"."-".$model->id,
                        'placeholder' => Module::t('amosadmin', 'Seleziona...'), 
                        'class' => "preferencecampaincontainer-preference_landing",
                        'disabled' => $model->preference_landing_id == null ? true : false,
                    ],
                ])->label(false) 
            ?>
        </div>
    </div>

    <!-- TODO inserire select option "Link a landing page esistente -->

</div>

<?php

/** PAGE SCRIPTS */
$script = <<< JS

    // RADIO Inserisci link
    $('input[name="inserisci_link[]"]').click(function(){
        
        if( $(this).val() == "content_news_link"){

            $(".preferencecampaincontainer-content_news_link").removeAttr("disabled");
            $(".preferencecampaincontainer-preference_landing").attr("disabled", true);

        }else if( $(this).val() == "preference_landing_id"){

            $(".preferencecampaincontainer-preference_landing").removeAttr("disabled");
            $(".preferencecampaincontainer-content_news_link").attr("disabled", true);
        }
    });

JS;

$this->registerJs($script);
?>