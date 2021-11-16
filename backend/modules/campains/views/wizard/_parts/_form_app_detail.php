<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use backend\modules\campains\models\PreferenceCampain;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use backend\modules\campains\Module;
use kartik\datecontrol\DateControl;
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
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_info"></use>
                </svg>
                <h5 class="font-weight-bold">
                    <?= "Informazioni di base dell'App" ?>
                </h5>
            </div>

            <div class="row">
                <div class="col-md-12 text-muted">
                    <p>
                        La data è utilizzata per l’ordinamento delle Comunicazioni nella vista a elenco nell’APP
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
          
                    <?= $form->field($model, 'title')->textInput(['maxlength' => "255"])->hint('max 255 caratteri') ?>

                    <?= $form->field($model, 'date_app')->widget(DateControl::classname(), [
                        'type' => DateControl::FORMAT_DATETIME,
                        'options' => [
                            'disabled' => false,
                        ],
                        'autoWidget' => true,
                        'saveOptions' => [
                            'class' => 'sr-only',
                        ],
                    ])->label('Data ordinamento') ?>
           
        </div>

    </div>

</div>