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

// $this->title                   = Yii::t('campains', 'Nuova Campagna: destinatari e canali');
// $this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
// $this->params['breadcrumbs'][] = $this->title;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

?>

<div class="preferences-center">
    <div class="row">
        <div class="col-md-12">
            <div class="py-4 d-inline-flex w-100 title-substeps affix-top">
                <svg class="icon mr-2">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_mail"></use>
                </svg>

                <h5 class="font-weight-bold">
                    <?= "Scegli il template dell'Email" ?>
                </h5>
            </div>

            <div class="row">
                <div class="col-md-12 text-muted">
                    <p>
                        Scegli un modello da inviare:
                    </p>
                </div>
                <div class="col-md-6 mt-4">
                    <?=
                        $form->field($model, 'template_id')->widget(Select2::classname(), [
                            'data' => $template,
                            'options' => [
                                'placeholder' => 'Select...',
                                'multiple' => false,
                                'id' => 'template-id'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Template');
                    ?>
                </div>
                <div class="col-md-12">
                    <?= Html::a('ANTEPRIMA', ['#'], ['class' => 'btn btn-xs btn-primary', 'id' => 'anteprima']) ?>
                </div>
                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id='modal-anteprima-id'>
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">


                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Anteprima Email
                                </h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <svg class="icon">
                                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_close"></use>
                                    </svg>
                                </button>
                            </div>
                            <div class="modal-body" id='preview-content-id'>

                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary btn-xs" data-dismiss="modal" type="button">Chiudi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </div>

    <?php

    $this->registerJs(
        <<<JS

$("a[id='anteprima']").click(function(event) {
    event.preventDefault();
    $.get("/campains/preference-campain/mailup-email-preview?id=" + $( "#template-id" ).val() , function( data ) {
        $("#preview-content-id" ).html( data );
        $('#modal-anteprima-id').modal('show');
    });
});
  
JS
,
        View::POS_READY
    );





    ?>

    <hr class="dashed mt-5 mb-5">

    <div class="row">

        <div class="col-md-12">
            <div class="py-4 d-inline-flex w-100 title-substeps affix-top">
                <svg class="icon mr-2">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_info"></use>
                </svg>
                <h5 class="font-weight-bold">
                    <?= "Informazioni di base dell'Email" ?>
                </h5>
            </div>

            <div class="row">
                <div class="col-md-12 text-muted">
                    <p>
                        La data di invio email è un'etichetta che verà mostrata sotto il titolo, non è la data in cui invierai effettivamente
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <?php
//            var_dump($model::className());
            ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => "255"])->hint('max 255 caratteri') ?>
            <?= $form->field($model, 'subtitle')->textInput(['maxlength' => "255"])->hint('max 255 caratteri') ?>
            <?= $form->field($model, 'date_newsletter')->textInput(['placeholder' => 'gg / mm / aa', 'maxlength' => "255"])->hint('max 255 caratteri') ?>
            <?= $form->field($model, 'subject')->textInput(['maxlength' => "255"])->hint('max 255 caratteri') ?>
        </div>

    </div>

</div>