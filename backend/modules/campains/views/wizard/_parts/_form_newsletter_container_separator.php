<?php 

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\modules\campains\Module;
use open20\amos\core\icons\AmosIcons;
use backend\modules\campains\utility\CampainsUtility;
use backend\modules\campains\models\PreferenceCampain;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

?>


<div class="newsletter-section">

    <?php 
        // echo "<pre>";
        // print_r($model->attributes);
        // echo "</pre>";

        // echo "<pre>";
        // print_r($model_preference_campain->attributes);
        // echo "</pre>";

    ?>

    <!-- START hidden input -->
    <?php 
        if( isset($model_preference_campain) ) {
            echo $form->field($model, 'preference_campain_id')->hiddenInput(['value'=> $model_preference_campain->id])->label(false);
        }
    ?>
    <?= $form->field($model, 'preference_campain_section_id')->hiddenInput([
        // 'id' => $model->isNewRecord ? $id = "preference_campain_section" : $id = "preference_campain_section-".$model->preference_campain_section_id,
        'class' => "preference_campain_section",
        'value'=> $model->preference_campain_section_id
    ])->label(false) ?>

    <?= $form->field($model, 'id')->hiddenInput(['value'=> $model->id])->label(false) ?>
    <?= $form->field($model, 'preference_campain_container_type_id')->hiddenInput(['value'=> $model->preference_campain_container_type_id])->label(false) ?>
    <!-- END hidden input -->


    <div class="row mt-5">
        <div class="col-md-12">
            <p>
               Il Separatore ti consente di introdurre una semplice barra con il titolo, per evidenziare ad esempio una sottosezione di contenuti
               senza creare una intera sezione. 
            </p>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'separator_title')->textInput([
                    'placeholder' => $model->getAttributeLabel('separator_title'),
                    'maxlength' => 255,
                ])->label('')->hint('max 255 caratteri') ?>
        </div>
    </div>


</div>
