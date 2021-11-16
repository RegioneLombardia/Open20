<?php 

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use backend\modules\campains\models\PreferenceCampain;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use backend\modules\campains\Module;
use yii\helpers\VarDumper;
use yii\web\View;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

$selectId = $model->isNewRecord ? "preference_center_section_color" : "preference_center_section_color_".$model->id;
$titleId = $model->isNewRecord ? "preference_center_section_title" : "preference_center_section_title_".$model->id;
$jsTitles = json_encode($newsletter_section_title);

if(empty($model->title)) {
    $this->registerJs(<<<JS
        $('#$selectId').change(function() {
            var option = $(this).find('option:selected').val();
            var titles = $jsTitles;
            //if (!$('#$titleId').val()) {
                $('#$titleId').val(titles[option]);
            //}
        });
        
JS
    , View::POS_READY);
}   

?>

<div class="newsletter-section">


    <!-- START HIDDEN INPUT -->
    <?php
        
        // PreferenceCampainChannelMm 
        if( isset($model_preference_campain_channel_mm) ) {
            echo $form->field($model, 'preference_campain_channel_mm_id')->hiddenInput(['value'=> $model_preference_campain_channel_mm->id])->label(false);
        }

        // PreferenceCampain
        if( isset($model_preference_campain) ) {
            echo $form->field($model, 'preference_campain_id')->hiddenInput(['value'=> $model_preference_campain->id])->label(false);
        }

        // PreferenceCampainSection
        echo $form->field($model, 'id')->hiddenInput(['value'=> $model->id])->label(false);

    ?>
    <!-- END HIDDEN INPUT -->

    <!-- <div class="row mt-5">
        <div class="col-md-12">
            <p>Scegli un colore di sfondo per questa sezione: per creare una sezione invisibile seleziona Trasparente</p>
        </div>
    </div> -->

    <div class="row mt-5">
        <div class="col-md-12">
            <?=
                // TODO BACKEND: gestire la lista dei colori, da dove estrarli
                $form->field($model, 'color')->widget(Select2::classname(), [
                    'data' => $newsletter_section_color,
                    'options' => [
                        'id' => $selectId,
                        'placeholder' => 'Seleziona...',
                        'multiple' => false,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Tipo sezione');
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'title')->textInput([
                    'placeholder' => $model->getAttributeLabel('title'),
                    'id' => $titleId,
                    'maxlength' => 255,
                ])->label('')->hint('max 255 caratteri') ?>
        </div>
    </div>

</div>
